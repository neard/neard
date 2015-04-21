Set objShell = WScript.CreateObject("WScript.Shell")
Set objFso = CreateObject("Scripting.FileSystemObject")
Set args = WScript.Arguments
num = args.Count
sargs = ""

If num = 0 Then
    WScript.Quit 1
End If

If num > 1 Then
    sargs = " "
    For k = 1 To num - 1
        anArg = args.Item(k)
        sargs = sargs & anArg & " "
    Next
End If

Return = objShell.Run("""" & args(0) & """" & sargs, 0, True)
