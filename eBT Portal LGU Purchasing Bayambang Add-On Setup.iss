[Setup]
AppId=eBTPortalLGUPurchasingBayambangAddOnSetup
AppName=eBT Portal LGU Purchasing Bayambang AddOn Setup
AppVerName=eBT Portal LGU Purchasing Bayambang AddOn Setup Version 1.0.0
AppPublisher=GreenPepper Software Inc.
AppPublisherURL=www.gps-sw.com
DefaultDirName={code:GetDefaultDir}
DisableProgramGroupPage=yes
OutputBaseFilename=eBTPortalLGUPurchasingBayambangAddOnSetup.exe
Compression=lzma
SolidCompression=yes
UsePreviousAppDir=no
AppendDefaultDirName=yes
AlwaysRestart=no
Uninstallable=yes
UninstallRestartComputer=no
TerminalServicesAware=yes
DisableDirPage=yes

[Files]
Source: LGU Purchasing Bayambang Add-On\*.*;DestDir: {app}; Flags: recursesubdirs
Source: eBTAddOnSetupLib.dll; DestDir: {sys}; Flags: uninsneveruninstall;

[Registry]

[Icons]

[Languages]
Name: en; MessagesFile: "compiler:Default.isl"

[Messages]
en.BeveledLabel=English

[CustomMessages]
en.MyDescription=My description
en.MyAppName=My Program
en.MyAppVerName=My Program %1

[Run]

[UninstallRun]

[Code]
//-Public Vars
var
AppDir : string;
FinishedInstall : Boolean;
FinishedUnInstall : Boolean;
AddOnName : string;
AddOnNameSpace : string;
AddOnID : string;
AddOnVersion : string;
AddOnCompanyName : string;
AddOnCompanyWebSite : string;
PortalDir : string;

//-External Functions (AddOnInstallAPI.dll)
function GetSetupFolder(): PChar; external 'GetSetupFolder@files:eBTAddOnSetupLib.dll stdcall';
function GetPortalFolder(): PChar; external 'GetPortalFolder@files:eBTAddOnSetupLib.dll stdcall';
function IsAddOnExists(AddOnName : PChar): boolean; external 'IsAddOnExists@files:eBTAddOnSetupLib.dll stdcall';
function AddOnRegister(AddOnName : PChar; AddOnNameSpace : PChar; AddOnID : PChar; AddOnVersion : PChar; AddOnCompanyName : PChar;AddOnCompanyWebSite: PChar): boolean; external 'AddOnRegister@files:eBTAddOnSetupLib.dll stdcall';
function AddOnUnRegister(AddOnName : PChar): boolean; external 'AddOnUnRegister@eBTAddOnSetupLib.dll stdcall';

procedure GetAddOnData();
begin
  AddOnName := 'LGU Purchasing Bayambang Add-On';
  AddOnNameSpace := 'GPS';
  AddOnID := 'LGUPurchasingBayambang';
  AddOnVersion := '1.0.0';
  AddOnCompanyName := 'GreenPepper Software Inc.';
  AddOnCompanyWebSite := 'www.gps-sw.com';
end;

function GetDefaultDir(Param : string): string;
begin
  //result := ExpandConstant('{pf}') + '\Fast Track Solution Sdn Bhd\PHP';
  result := PortalDir + '\AddOns\' + AddOnNameSpace + '\' + AddOnName;
end;

function InitializeUninstall(): Boolean;
begin
  Result := True;
  GetAddOnData();
end;

function InitializeSetup(): Boolean;
var
ResultCode: Integer;
SetupDir : string;
begin
  Result := True;
  GetAddOnData();
  SetupDir := GetSetupFolder();
  PortalDir := GetPortalFolder();
  if SetupDir <> '' then begin
    if PortalDir <> '' then begin
      if IsAddOnExists(AddOnName) then begin
        Exec(PortalDir + '\AddOns\' + AddOnNameSpace + '\' + AddOnName + '\unins000.exe', '', '', SW_HIDE, ewWaitUntilTerminated, ResultCode);
        result := false;
      end
      else begin
      end;
    end
    else begin
      MsgBox('eBT Portal is not configured.', mbInformation, MB_OK);
      result := false;
    end;
  end
  else begin
    MsgBox('eBT Portal is not installed.', mbInformation, MB_OK);
    result := false;
  end;

end;

function NextButtonClick(CurPageID: Integer): Boolean;
var
  ResultCode: Integer;

begin

  Result := True;

  case CurPageID of

    wpSelectDir :
    begin
      AppDir := ExpandConstant('{app}');
    end;
    wpFinished :
    begin
      //-If All OK then
      if FinishedInstall then begin
        if AddOnRegister(AddOnName,AddOnNameSpace,AddOnID,AddOnVersion,AddOnCompanyName,AddOnCompanyWebSite) = false then begin
          MsgBox('eBT Portal Add-On ['+AddOnNameSpace + '\' + AddOnName+'] failed to register.', mbError, MB_OK);
        end;
      end;
      if FinishedUnInstall then begin
      end;
    end;
  end;

end;

procedure CurStepChanged(CurStep: TSetupStep);
begin
  if CurStep = ssPostInstall then begin
    FinishedInstall := True;
  end;
end;

procedure CurUninstallStepChanged(CurUninstallStep: TUninstallStep);
var
  ResultCode: Integer;
begin
  if CurUninstallStep = usPostUninstall then begin
    if AddOnUnRegister(AddOnName) = false then begin
      MsgBox('eBT Portal Add-On ['+AddOnNameSpace + '\' + AddOnName+'] failed to unregister.', mbError, MB_OK);
    end;
  end;
end;
