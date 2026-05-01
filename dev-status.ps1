$ErrorActionPreference = 'Stop'

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

function Write-ServiceStatus {
    param(
        [string]$Label,
        [string]$Url,
        [bool]$IsUp
    )

    $statusText = if ($IsUp) { 'UP' } else { 'DOWN' }
    $statusColor = if ($IsUp) { 'Green' } else { 'Red' }

    Write-Host ($Label.PadRight(18)) -NoNewline
    Write-Host $statusText -ForegroundColor $statusColor -NoNewline
    Write-Host "  $Url"
}

$laravelUp = Test-TcpPort -Address '127.0.0.1' -Port 8000
$mailpitUiUp = Test-TcpPort -Address '127.0.0.1' -Port 8025
$mailpitSmtpUp = Test-TcpPort -Address '127.0.0.1' -Port 1025

Write-Host ''
Write-Host 'Local Dev Status' -ForegroundColor Cyan
Write-Host '----------------'
Write-ServiceStatus -Label 'Laravel' -Url 'http://127.0.0.1:8000' -IsUp $laravelUp
Write-ServiceStatus -Label 'Mailpit UI' -Url 'http://127.0.0.1:8025' -IsUp $mailpitUiUp
Write-ServiceStatus -Label 'Mailpit SMTP' -Url '127.0.0.1:1025' -IsUp $mailpitSmtpUp
Write-Host ''

if ($laravelUp -and $mailpitUiUp -and $mailpitSmtpUp) {
    Write-Host 'All local dev services are running.' -ForegroundColor Green
} else {
    Write-Host 'One or more local dev services are down.' -ForegroundColor Yellow
    Write-Host 'Run .\start-local-dev.ps1 to bring them back up.'
}
