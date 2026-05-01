$ErrorActionPreference = 'Stop'

$projectRoot = $PSScriptRoot
$storageLogs = Join-Path $projectRoot 'storage\logs'

$phpExe = 'C:\xampp\php\php.exe'
$mailpitExe = 'C:\Users\julie\AppData\Local\Microsoft\WinGet\Packages\axllent.mailpit_Microsoft.Winget.Source_8wekyb3d8bbwe\mailpit.exe'

$laravelLog = Join-Path $storageLogs 'local-dev-laravel.log'
$laravelErrorLog = Join-Path $storageLogs 'local-dev-laravel-error.log'
$mailpitLog = Join-Path $storageLogs 'local-dev-mailpit.log'
$mailpitErrorLog = Join-Path $storageLogs 'local-dev-mailpit-error.log'

function Test-TcpPort {
    param(
        [string]$Address,
        [int]$Port
    )

    $client = New-Object System.Net.Sockets.TcpClient
    try {
        $async = $client.BeginConnect($Address, $Port, $null, $null)
        $connected = $async.AsyncWaitHandle.WaitOne(750)
        if (-not $connected) {
            return $false
        }

        $client.EndConnect($async)
        return $true
    } catch {
        return $false
    } finally {
        $client.Dispose()
    }
}

function Wait-ForPort {
    param(
        [string]$Address,
        [int]$Port,
        [int]$Retries = 20
    )

    for ($i = 0; $i -lt $Retries; $i++) {
        if (Test-TcpPort -Address $Address -Port $Port) {
            return $true
        }

        Start-Sleep -Milliseconds 500
    }

    return $false
}

if (-not (Test-Path $storageLogs)) {
    New-Item -ItemType Directory -Path $storageLogs -Force | Out-Null
}

if (-not (Test-Path $phpExe)) {
    throw "PHP executable not found at $phpExe"
}

if (-not (Test-Path $mailpitExe)) {
    throw "Mailpit executable not found at $mailpitExe"
}

$laravelRunning = Test-TcpPort -Address '127.0.0.1' -Port 8000
$mailpitUiRunning = Test-TcpPort -Address '127.0.0.1' -Port 8025
$mailpitSmtpRunning = Test-TcpPort -Address '127.0.0.1' -Port 1025

if (-not $laravelRunning) {
    $laravelProcess = Start-Process -FilePath $phpExe `
        -ArgumentList @('artisan', 'serve', '--host=127.0.0.1', '--port=8000') `
        -WorkingDirectory $projectRoot `
        -RedirectStandardOutput $laravelLog `
        -RedirectStandardError $laravelErrorLog `
        -WindowStyle Hidden `
        -PassThru

    if (-not (Wait-ForPort -Address '127.0.0.1' -Port 8000)) {
        throw 'Laravel server failed to start on 127.0.0.1:8000'
    }
}

if (-not ($mailpitUiRunning -and $mailpitSmtpRunning)) {
    $mailpitProcess = Start-Process -FilePath $mailpitExe `
        -ArgumentList @('--smtp', '127.0.0.1:1025', '--listen', '127.0.0.1:8025') `
        -WorkingDirectory $projectRoot `
        -RedirectStandardOutput $mailpitLog `
        -RedirectStandardError $mailpitErrorLog `
        -WindowStyle Hidden `
        -PassThru

    if (-not (Wait-ForPort -Address '127.0.0.1' -Port 8025)) {
        throw 'Mailpit UI failed to start on 127.0.0.1:8025'
    }

    if (-not (Wait-ForPort -Address '127.0.0.1' -Port 1025)) {
        throw 'Mailpit SMTP failed to start on 127.0.0.1:1025'
    }
}

Write-Host ''
Write-Host 'Local dev services are ready.' -ForegroundColor Green
Write-Host 'Laravel : http://127.0.0.1:8000'
Write-Host 'Mailpit : http://127.0.0.1:8025'
Write-Host ''
Write-Host 'Logs:'
Write-Host "  $laravelLog"
Write-Host "  $laravelErrorLog"
Write-Host "  $mailpitLog"
Write-Host "  $mailpitErrorLog"
