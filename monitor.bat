@echo off 
echo ******************** �{������ ************************* >>E:\log\st_server.log
echo �ɶ��G%date% %time% >>E:\log\st_server.log
tasklist /FI "IMAGENAME eq StServer.exe" /FO CSV > E:\log\st_temp.log
FOR /F %%A IN (E:\log\st_temp.log) do if %%A == ��T: goto process_off
:process_on
echo �{�������s�b���\ >>>E:\log\st_server.log
goto end
:process_off
E:\ST\StServer.exe [ĵ�i] �{���������ѭ��s�Ұ� -m �{���������ѭ��s��
��,�o�ͮɶ� %date% %time% -t
echo �{���������s�b���s�Ұ� >>f:\log\Detection.log
start E:\ST\StServer.exe
:end
echo ************************************************************* >>E:\log\st_server.log