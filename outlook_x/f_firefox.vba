Sub export()
        Dim objApplication As Outlook.Application
        Dim objNameSpace As Outlook.NameSpace
        Dim objAppointments As Outlook.MAPIFolder
        Dim urlimport As String
        Dim pathFireFox As String
        pathFireFox = "C:\Program Files\Mozilla Firefox\Firefox.exe"
        Set objApplication = CreateObject("Outlook.Application")
        Set objNameSpace = objApplication.GetNamespace("MAPI")
        Set objAppointments = objNameSpace.GetDefaultFolder(olFolderCalendar)
        Dim ie As Object
        Set ie = CreateObject("Internetexplorer.Application")
        Set objAppointment = Application.ActiveInspector.CurrentItem
        urlimport = "https://cenwms.xyz/analytique.php?datedebut=" & DateDiff("s", #1/1/1970#, Format(objAppointment.Start, "yyyy/mm/dd hh:mm:ss")) & _
                    "&datefin=" & DateDiff("s", #1/1/1970#, Format(objAppointment.End, "yyyy/mm/dd hh:mm:ss")) & _
                    "&objet=" & Replace(objAppointment.Subject, " ", "_") & _
                    "&remarque=" & Replace(objAppointment.Body, " ", "_")
        Shell """" & pathFireFox & """" & " -new-window  " & urlimport, vbHide
End Sub