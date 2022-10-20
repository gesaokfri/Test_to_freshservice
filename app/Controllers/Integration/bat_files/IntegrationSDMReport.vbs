Set WshShell = CreateObject("WScript.Shell" ) 
WshShell.Run chr(34) & "D:\xampp\htdocs\Dashboard-AtmaJaya\app\Controllers\Integration\bat_files\IntegrationSDMReport.bat" & Chr(34), 0 
Set WshShell = Nothing