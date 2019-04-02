set WshShell = CreateObject("WScript.Shell")
WScript.Sleep 2000
WshShell.SendKeys "%(FT)"

WScript.Sleep 1000
Function LPad (str, pad, length)
    LPad = String(length - Len(str), pad) & str
End Function
WScript.Sleep 1000
myy = Year(Now)
mym = LPad(Month(Now),"0",2)
myd = LPad(Day(Now),"0",2)
WshShell.SendKeys myy & mym & myd & ".msg"
WScript.Sleep 1000
WshShell.SendKeys "{ENTER}"
WScript.Sleep 1000
WshShell.SendKeys "%{T}"
WScript.Sleep 1000
WshShell.SendKeys "{ENTER}"
WScript.Sleep 1000
WshShell.SendKeys "%{S}"
WScript.Sleep 1000
WshShell.SendKeys "%{Y}"
WScript.Sleep 1000
WshShell.SendKeys "%(FC)"
