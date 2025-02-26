<?php
/**************************************************************************************************************

    NAME
        RandomAccessFile.phpclass

    DESCRIPTION
        Implements a random file with fixed-length records.

    AUTHOR
        Christian Vigh, 03/2016.

    HISTORY
    [Version : 1.0]	[Date : 2016/03/25]     [Author : CV]
	Initial version.

    [Version : 1.1]	[Date : 2016/07/09]     [Author : CV]
	. Handled the possibility to have a fixed-size header in the file.
	. Added the $header_size parameter to the constructor

 **************************************************************************************************************/

/*==============================================================================================================

    class RandomAccessFile -
        Implements a random file with fixed-length records.

  ==============================================================================================================*/
class  RandomAccessFile		implements	\ArrayAccess, \Countable, \Iterator
   {
	public		$Filename ;					// Random file name
	public		$RecordSize ;					// Random file record size
	public		$CacheSize ;					// Cache size, in # of entries
	public		$Filler ;					// Filler character
	public		$Header			=  false ;		// Header contents
	public		$HeaderSize		=  false ;		// Header size 

	public		$CacheMisses,					// When the cache is enabled, stores the number of cache hits & misses
			$CacheHits ;

	protected	$Cache ;					// Record cache
	protected	$fd			=  null ;		// File descriptor
	protected	$StatInfo ;					// Stat information (specially used for retrieving file size)
	protected	$ReadOnly ;					// True if the file has been opened in read-only mode
	protected	$EmptyRecord		=  null ;		// Data that represents a null record, containing only filler characters
	protected	$HeaderSizeCallback	=  false ;


	/*--------------------------------------------------------------------------------------------------------------
	
	    CONSTRUCTOR -
	        Instantiates a RandomAccessFile object, without opening the specified file.
	
	    PROTOTYPE
	        $rf	=  new  RandomAccessFile ( $filename, $record_size, $cache_size = false, $filler = "\0", $header_size = false ) ;
	
	    PARAMETERS
	        $filename (string) -
	                Random file name.

		$record_size (integer) -
			Size of a record.

		$header_size (integer or callback) -
			Size of an optional header or a callback which must have the following signature :

				integer function  compute_size ( $fd ) ;

			This allows the caller to process files having a variable-length header, whose length is
			specified in some fixed-length part of it. The callback can then use the $fd file descriptor
			to read the data at the offset containing the header size and return the computed size.
			Note that when the callback is called, the offset in file is guaranteed to be at position 0.

		$cache_size (integer) -
			When not null, indicates how many records from the random file should be cached into memory.

		$filler (char) -
			Character to be used for filling when an incomplete record is written.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  __construct  ( $filename, $record_size, $header_size = false, $cache_size = false, $filler = "\0" )
	   {
		$this -> Filename	=  $filename ;
		$this -> RecordSize	=  $record_size ;
		$this -> Filler		=  ( strlen ( $filler ) ) ?  substr ( $filler, 0, 1 ) : "\0" ;

		if  ( is_numeric ( $header_size ) )		// Fixed header size 
			$this -> HeaderSize	=  ( integer ) $header_size ;
		else if  ( is_callable ( $header_size ) )	// Header size will be computed when the file will be opened
			$this -> HeaderSizeCallback	=  $header_size ;
		else						// No header
			$this -> HeaderSize	=  0 ;

		$this -> __set_cache_size ( $cache_size ) ;
		$this -> __reset ( ) ;
	    }


	/**************************************************************************************************************
	 **************************************************************************************************************
	 **************************************************************************************************************
	 ******                                                                                                  ******
	 ******                                                                                                  ******
	 ******                                        PRIVATE FUNCTIONS                                         ******
	 ******                                                                                                  ******
	 ******                                                                                                  ******
	 **************************************************************************************************************
	 **************************************************************************************************************
	 **************************************************************************************************************/

	// __add_cache_entry :
	//	Adds a record entry into the cache.
	private function  __add_cache_entry ( $record, $data )
	   {
		// Cache disabled
		if  ( ! $this -> CacheSize ) 
			return ;
		// Record already exists in the cache
		else if  ( isset ( $this -> Cache [ $record ] ) )
		   {
			$this -> Cache [ $record ]	=  [ 'time' => microtime ( true ), 'data' => $data ] ;
			return ;
		    }
		// Record does not exist in the cache, which is full and needs to be adjusted.
		// The oldest cache entry will be removed to allow this new record to be cached
		else if  ( count ( $this -> Cache ) + 1  >=  $this -> CacheSize )
		   {
			$oldest_time	=  PHP_INT_MAX ;
			$oldest_record	=  PHP_INT_MAX ;

			foreach  ( $this -> Cache  as  $record => $entry )
			   {
				if  ( $entry [ 'time' ]  <  $oldest_time )
				   {
					$oldest_time	=  $entry [ 'time' ] ;
					$oldest_record	=  $record ;
				    }
			    }

			unset ( $this -> Cache [ $oldest_record ] ) ;
		    }

		// Add this record to the cache
		$this -> Cache [ $record ]	=  [ 'time' => microtime ( true ), 'data' => $data ] ;
	    }


	// __ensure_opened :
	//	Throws an exception if the random file is not yet opened.
	private function  __ensure_opened ( $message = null )
	   {
		if  ( ! $this -> fd )
		   {
			if  ( ! $message )
				$message	=  "Cannot process requested operation : file \"{$this -> Filename}\" is not opened." ;

			throw ( new \RuntimeException ( $message ) ) ;
		    }
	    }


	// __ensure_writeable :
	//	Ensures that the random file is opened in write mode, otherwise throws an exception.
	private function  __ensure_writable ( )
	   {
		$this -> __ensure_opened ( ) ;

		if  ( $this -> ReadOnly )
			throw ( new \RuntimeException ( "Cannot process requested operation : file \"{$this -> Filename}\" " .
					"has been opened in read-only mode." ) ) ;
	    }


	// __get_empty_record :
	//	Creates an empty record of $RecordSize bytes, if not already done.
	private function  __get_empty_record ( )
	   {
		if  ( $this -> EmptyRecord  ===  null )
			$this -> EmptyRecord	=  str_repeat ( $this -> Filler, $this -> RecordSize ) ;

		return ( $this -> EmptyRecord ) ;
	    }


	// __get_record_count -
	//	Returns the number of records present in the random file.
	private function  __get_record_count ( )
	   {
		$this -> __ensure_opened ( ) ;

		if  ( ! $this -> StatInfo )
			$this -> __update_stat_info ( ) ;

		$size		=  $this -> StatInfo [ 'size' ] - $this -> HeaderSize ;
		$records	=  ( integer ) ( ( $size + $this -> RecordSize - 1 ) / $this -> RecordSize ) ;

		return ( $records ) ;
	    }


	// __get_record_offset -
	//	Returns the byte offset of a record, taking into account the record size and the optional header size.
	private function  __get_record_offset ( $record_number )
	   {
		return ( $this -> HeaderSize + ( $record_number * $this -> RecordSize ) ) ;
	    }


	// __read_record :
	//	Returns the data for the specified record, or false if the record does not exist.
	private function  __read_record ( $record )
	   {
		if  ( isset ( $this -> Cache [ $record ] ) )
		   {
			$this -> CacheHits ++ ;
			return ( $this -> Cache [ $record ] [ 'data' ] ) ;
		    }
		else if  ( $record  >=  0  &&  $record  <  $this -> __get_record_count ( ) )
		   {
			$this -> CacheMisses ++ ;

			fseek ( $this -> fd, $this -> __get_record_offset ( $record ), SEEK_SET ) ;
			$data	=  fread ( $this -> fd, $this -> RecordSize ) ;

			if  ( strlen ( $data ) )
				$this -> __add_cache_entry ( $record, $data ) ;

			return ( $data ) ;
		    }
		else
			return ( false ) ;
	    }


	// __reset :
	//	Closes the random file, if opened, and resets all the properties as if the object was just instantiated.
	private function  __reset ( )
	   {
		if  ( $this -> fd )
			fclose ( $this -> fd ) ;

		$this -> fd		=  null ;
		$this -> Cache		=  [] ;
		$this -> StatInfo	=  null ;
		$this -> ReadOnly	=  null ;
		$this -> CacheMisses	=  0 ;
		$this -> CacheHits	=  0 ;
		$this -> Header		=  false ;
	    }


	// __set_cache_size :
	//	Sets the cache size.
	private function  __set_cache_size ( $size )
	   {
		$this -> Cache		=  [] ;
		$this -> CacheSize	=  $size ;
	    }


	// __update_stat_info :
	//	Updates stat information for this file.
	private function  __update_stat_info ( )
	   {
		$this -> StatInfo	=  fstat ( $this -> fd ) ;

		if  ( ! $this -> StatInfo )
			throw ( new \RuntimeException ( "Unable to stat file \"{$this -> Filename}\"." ) ) ;
	    }


	// __write_record :
	//	Writes a record, and add it to the cache if needed.
	private function  __write_record ( $record, $data, $cache =  false )
	   {
		$offset		=  $this -> __get_record_offset ( $record ) ;

		fseek ( $this -> fd, $offset, SEEK_SET ) ;
		$written	=  fwrite ( $this -> fd, $data ) ;

		if  ( $cache )
			$this -> __add_cache_entry ( $record, $data ) ;

		return ( ( $written  ==  $this -> RecordSize ) ?  true : false ) ;
	    }



	/**************************************************************************************************************
	 **************************************************************************************************************
	 **************************************************************************************************************
	 ******                                                                                                  ******
	 ******                                                                                                  ******
	 ******                                          PUBLIC METHODS                                          ******
	 ******                                                                                                  ******
	 ******                                                                                                  ******
	 **************************************************************************************************************
	 **************************************************************************************************************
	 **************************************************************************************************************/

	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        Close - Closes the random file.
	
	    PROTOTYPE
	        $rf -> Close ( ) ;
	
	    DESCRIPTION
	        Closes the random file. 
	
	    RETURN VALUE
	        True if the file was opened, false otherwise.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  Close ( )
	   {
		if  ( $this -> fd )
		   {
			fclose ( $this -> fd ) ;
			$this -> __reset ( ) ;

			return ( true ) ;
		    }
		else
			return ( false ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        Copy - Copy records
	
	    PROTOTYPE
	        $rf -> Copy ( $from, $to, $count = 1 ) ;
	
	    DESCRIPTION
	        Copies $count records starting from record $from to the destination $to.
		This method can handle situations where origin and destination overlap.
	
	    PARAMETERS
	        $from (integer) -
	               Source record number (starting from zero).

		$to (integer) -
			Destination record number.

		$count (integer) -
			Number of records to copy.
	
	    RETURN VALUE
	        Returns the number of records effectively copied. This value can be lower than the specified number of
		records if :
		- $from + $count - 1 goes past the end of file
		- A read or write error occurred during the copy (consider this as a paranoid check)
	
	    NOTES
	        . The $to parameter can be specified past the end of file. In this case intermediate records will be 
		  created using the filler character.
		. Similarly, at some point during the copy, the current destination record can go past the end of file ;
		  in this case, the new record(s) will be appended to the random file.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  Copy ( $from, $to, $count = 1 )
	   {
		$this -> __ensure_writable ( ) ;
		$max	=  $this -> __get_record_count ( ) ;

		// Don't do anything if we try to start a copy past the end of file...
		if  ( $from  >=  $max  ||  $from  ==  $to )
			return ( 0 ) ;

		// If the number of records to be copied makes that we would go past the end of file, adjust it
		if  ( $from + $count  >=  $max )
			$count		=  $max - $from ;

		// Origin is lower than destination - check that the copy does not overlap
		if (  $from <  $to )
		   {
			// Overlapping occurs - we will copy backwards, from the last record of $from
			// ($from + count - 1) to $from
			if  ( $to <  $from + $count - 1 )
			   {
				 $from  =  $from + $count - 1 ;
				 $to	=  $to   + $count - 1 ;
				 $incr  =  -1 ;
			     }
			else
				$incr     =  1 ;
		     }
		else
			$incr     =  1 ;

		$copied_records	=  0 ;
		$file_expanded	=  ( $to + $count  >=  $max ) ;

		// Copy loop - break it on whatever error could be encountered during copy 
		while  ( $count -- )
		   {
			$data		=   $this -> Read ( $from ) ;

			if  ( $data  ===  false )
				break ;

			$status	=  $this -> Write ( $to, $data ) ;

			if  ( $status  ===  false )
				break ;

			$from       +=  $incr ;
			$to         +=  $incr ;

			$copied_records ++ ;
		     }

		// Our stat information may not be accurate now...
		if  ( $file_expanded )
			$this -> StatInfo	=  null ;

		// All done, return the number of records copied
		return ( $copied_records ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        IsOpened - Checks if the current random file is opened.
	
	    PROTOTYPE
	        $status		=  $rf -> IsOpened ( ) ;
	
	    DESCRIPTION
	        Checks if the current random file is already opened.
	
	    RETURN VALUE
	        True if the file is opened, false otherwise.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  IsOpened ( )
	   {
		return ( $this -> fd  !==  null ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        IsReadonly - Checks if the current random file is opened in read-only mode.
	
	    PROTOTYPE
	        $status		=  $rf -> IsReadOnly ( ) ;
	
	    DESCRIPTION
	        Checks if the current random file is opened in read-only mode.
	
	    RETURN VALUE
	        True if the file is opened in read-only mode, false otherwise.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  IsReadOnly ( )
	   {
		return ( $this -> ReadOnly ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        Open - Opens the instantiated random file.
	
	    PROTOTYPE
	        $rf -> Open ( $read_only = false ) ;
	
	    DESCRIPTION
	        Opens the random file that has been instantiated.
		Reads the header data if defined, otherwise calls the $HeaderSizeCallback callback, which can be
		specified when instantiating the object, to get the real header size if the header size is contained in 
		the header information itself.
	
	    PARAMETERS
	        $read_only (boolean) -
	                By default, a random file is opened in write mode. Specify true to open it in read-only mode.

	 *-------------------------------------------------------------------------------------------------------------*/
	public function  Open ( $read_only = false )
	   {
		$this -> fd	=  @fopen ( $this -> Filename, ( $read_only ) ?  "r" : "r+" ) ;

		if  ( ! $this -> fd )
		   {
			$err	=  error_get_last ( ) ;
			throw ( new \RuntimeException ( $err [ 'message' ] ) ) ;
		    }

		$this -> __update_stat_info ( ) ;
		$this -> ReadOnly	=  $read_only ;

		if  ( $this -> HeaderSizeCallback )
		   {
			fseek ( $this -> fd, 0, SEEK_SET ) ;
			$callback		=  $this -> HeaderSizeCallback ;
			$this -> HeaderSize	=  $callback ( $this -> fd ) ;
			fseek ( $this -> fd, 0, SEEK_SET ) ;
		    }

		//$this -> Header		=  fread ( $this -> fd, $this -> HeaderSize ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        Read - Reads the specified record.
	
	    PROTOTYPE
		$data	=  $rf -> Read ( $record ) ;
	
	    DESCRIPTION
	        Reads the specified record.
	
	    PARAMETERS
	        $record (integer) -
	                Number of the record to be read. Record numbers start from zero.
	
	    RETURN VALUE
	        Returns the record data, or false if the specified record number is out of range.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  Read ( $record )
	   {
		$this -> __ensure_opened ( ) ;
		$max	=  $this -> __get_record_count ( ) ;

		if  ( $record  <  0  &&  $record  >=  $max )
			throw ( new \RuntimeException ( $record ) ) ;

		return  ( $this -> __read_record ( $record ) ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        Swap - Swaps two record contents.
	
	    PROTOTYPE
	        $rf -> Swap ( $from, $to, $count = 1 ) ;
	
	    DESCRIPTION
	        Swaps the contents of the specified records.
	
	    PARAMETERS
	        $from (integer) -
	                Start index of the record(s) to be swapped. Indexes start from zero.

		$to (integer) -
			Destination index.

		$count (integer) -
			Number of records to be swapped.
	
	    RETURN VALUE
	        Returns the actual number of records swapped.

	    NOTES
		Results may seem counter-intuitive when the source and destination ranges overlap. This feature may be
		removed in future releases.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  Swap ( $from, $to, $count = 1 )
	   {
		$this -> __ensure_writable ( ) ;
		$max	=  $this -> __get_record_count ( ) ;

		// Don't do anything if we try to start a copy past the end of file...
		if  ( $from  >=  $max  ||  $from  ==  $to )
			return ( 0 ) ;

		// If the number of records to be copied makes that we would go past the end of file, adjust it
		if  ( $from + $count  >=  $max )
			$count		=  $max - $from ;

		// Origin is lower than destination - check that the copy does not overlap
		if (  $from <  $to )
		   {
			// Overlapping occurs - we will copy backwards, from the last record of $from
			// ($from + count - 1) to $from
			if  ( $to <  $from + $count - 1 )
			   {
				 $from  =  $from + $count - 1 ;
				 $to	=  $to   + $count - 1 ;
				 $incr  =  -1 ;
			     }
			else
				$incr     =  1 ;
		     }
		else
			$incr     =  1 ;

		$swapped_records	=  0 ;
		$file_expanded	=  ( $to + $count  >=  $max ) ;

		// Swap loop - break it on whatever error could be encountered during copy 
		while  ( $count -- )
		   {
			$from_data	=   $this -> Read ( $from ) ;

			if  ( $from_data  ===  false )
				break ;

			$to_data	=  $this -> Read ( $to ) ;

			if  ( $to_data  ===  false )
				break ;

			$status	=  $this -> Write ( $to, $from_data ) ;

			if  ( $status  ===  false )
				break ;

			$status =  $this -> Write ( $from, $to_data ) ;

			if  ( $status  ===  false )
				break ;

			$from       +=  $incr ;
			$to         +=  $incr ;

			$swapped_records ++ ;
		     }

		// Our stat information may not be accurate now...
		if  ( $file_expanded )
			$this -> StatInfo	=  null ;

		// All done, return the number of records copied
		return ( $swapped_records ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        Truncate - Truncates a random file.
	
	    PROTOTYPE
	        $rf -> Truncate ( $start_record ) ;
	
	    DESCRIPTION
	        Trucates a random file, starting from the specified record.
	
	    PARAMETERS
	        $start_record (integer) -
	                Index of the first record to be deleted. Truncate (0) will reset file contents.
	
	    RETURN VALUE
	        True if the operation succeeded, false otherwise.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  Truncate ( $start_record = 0 )
	   {
		$max	=  $this -> __get_record_count ( ) ;

		if  ( $start_record  >=  $max )
			return ( 0 ) ;

		$status		=  ftruncate ( $this -> fd, $this -> __get_record_offset ( $start_record ) ) ;

		$this -> StatInfo	=  null ;

		return ( $status ) ;
	    }


	/*--------------------------------------------------------------------------------------------------------------
	
	    NAME
	        Write - Writes a record.
	
	    PROTOTYPE
	        $rf -> Write ( $record, $data ) ;
	
	    DESCRIPTION
	        Writes data in the specified record.
	
	    PARAMETERS
	        $record (integer) -
	                Record number. If the specified record number is past the end of file, then empty records will
			be added using the filler character.

		$data (string) -
			Record data. If the data is greater than the file's record size, it will be truncated. If
			smaller, it will be padded using the filler character.
	
	    RETURN VALUE
	        True if the operation succeded, false otherwise.
	
	    NOTES
	        The last record of an existing file can be incomplete (such a situation is allowed for example when you
		use the RandomAccessFile class for fast access to existing text files). 
		If the specified record number is the end of file, then the last incomplete record (if any) will be 
		filled using the filler character and intermediate records initialized with the filler character will 
		be inserted as needed.
	
	 *-------------------------------------------------------------------------------------------------------------*/
	public function  Write ( $record, $data ) 
	   {
		$this -> __ensure_writable ( ) ;
		$max	=  $this -> __get_record_count ( ) ;

		// If the specified record is past the end of file, then we will have to insert empty records
		if  ( $record  >=  $max )
		   {
			// Check if the last record is partial
			$remainder	=  ( $this -> StatInfo [ 'size' ] - $this -> HeaderSize ) % $this -> RecordSize ;

			// Last record is partial - write enough bytes to fit the file's record size
			if  ( $remainder )
			   {
				$bytes_to_write		=  $this -> RecordSize - $remainder ;
				$empty_data		=  str_repeat ( $this -> Filler, $bytes_to_write ) ;

				fseek ( $this -> fd, $this -> StatInfo [ 'size' ], SEEK_SET ) ;
				$written	=  fwrite ( $this -> fd, $empty_data ) ;

				if  ( $written  !=  $bytes_to_write )
					return ( false ) ;
			    }

			// Write empty records until the specified record index is reached
			$empty_record	=  $this -> __get_empty_record ( ) ;

			for  ( $i = $max ; $i  <  $record ; $i ++ )
			   {
				$status		=  $this -> __write_record ( $i, $empty_record, false ) ;

				if  ( ! $status )
					return ( false ) ;
			    }
		    }

		// Pad or truncate record data, if needed
		$length		=  strlen ( $data ) ;

		if  ( $length  >  $this -> RecordSize )
			$data	 =  substr ( $data, 0, $this -> RecordSize ) ;
		else if  ( $length  <  $this -> RecordSize )
			$data	.=  str_repeat ( $this -> Filler, $this -> RecordSize - $length ) ;

		// Write record
		$status			=  $this -> __write_record ( $record, $data, true ) ;
		$this -> StatInfo	=  null ;

		return ( $status ) ;
	    }


	/**************************************************************************************************************
	 **************************************************************************************************************
	 **************************************************************************************************************
	 ******                                                                                                  ******
	 ******                                                                                                  ******
	 ******                                    INTERFACES IMPLEMENTATION                                     ******
	 ******                                                                                                  ******
	 ******                                                                                                  ******
	 **************************************************************************************************************
	 **************************************************************************************************************
	 **************************************************************************************************************/

	// Countable interface
	public function  count ( )
	   {
		return ( $this -> __get_record_count ( ) ) ;
	    }


	// ArrayAccess interface
	public function  offsetExists ( $offset )
	   {
		$max	=  $this -> __get_record_count ( ) ;

		if  ( $offset  >=  0  &&  $offset  <  $max )
			return ( true ) ;
		else
			return ( false ) ;
	    }

	public function  offsetGet ( $offset )
	   {
		return ( $this -> Read ( $offset ) ) ;
	    }


	public function  offsetSet ( $offset, $value )
	   {
		if  ( $offset  ===  null )
			$offset		=  $this -> __get_record_count ( ) ;

		$this -> Write ( $offset, $value ) ;
	    }


	public function  offsetUnset ( $offset )
	   {
		error ( new \RuntimeException ( ) ) ;
	    }


	// Iterator interface
	private		$iterator_position	=  0 ;

	public function  rewind ( )
	   {
		$this -> iterator_position	=  0 ;
	    }

	public function  key ( )
	   {
		return ( $this -> iterator_position ) ;
	    }


	public function  current ( )
	   {
		return ( $this -> Read ( $this -> iterator_position ) ) ;
	    }


	public function  next ( )
	   {
		$this -> iterator_position ++ ;
	    }

	public function  valid ( )
	   {
		return ( $this -> iterator_position  <  count ( $this ) ) ;
	    }
    }