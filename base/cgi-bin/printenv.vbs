'!c:/windows/system32/cscript //nologo
''
''  printenv -- demo CGI program which just prints its environment
''

WScript.Echo "Content-type: text/plain" & vbLF
Set wshShell = CreateObject("WScript.Shell")
Set wshEnv = wshShell.Environment("Process")
For Each strItem In wshEnv
	WScript.Echo strItem
Next
Set wshShell = Nothing
Set wshEnv = Nothing
Wscript.Quit 0
