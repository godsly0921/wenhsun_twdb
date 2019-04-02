@echo off 
echo ******************** 程式偵測 ************************* >>E:\log\st_server.log
echo 時間：%date% %time% >>E:\log\st_server.log
tasklist /FI "IMAGENAME eq StServer.exe" /FO CSV > E:\log\st_temp.log
FOR /F %%A IN (E:\log\st_temp.log) do if %%A == 資訊: goto process_off
:process_on
echo 程式偵測存在成功 >>>E:\log\st_server.log
goto end
:process_off
E:\ST\StServer.exe [警告] 程式偵測失敗重新啟動 -m 程式偵測失敗重新啟
動,發生時間 %date% %time% -t
echo 程式偵測不存在重新啟動 >>f:\log\Detection.log
start E:\ST\StServer.exe
:end
echo ************************************************************* >>E:\log\st_server.log