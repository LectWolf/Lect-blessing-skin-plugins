param (
    [Parameter(Mandatory, Position = 0)]
    [string]
    $Id,

    [Parameter(Mandatory)]
    [string]
    $Author,

    [Parameter(Mandatory)]
    [string]
    $Namespace,

    [Parameter()]
    [string]
    $Url,

    [Parameter()]
    [switch]
    [Alias('Views')]
    $View,

    [Parameter()]
    [switch]
    [Alias('Assets')]
    $Asset
)

$manifest = [PSCustomObject]@{
    name        = $Id
    title       = "$Namespace::general.title"
    version     = '0.1.0'
    description = "$Namespace::general.description"
    author      = $Author
    url         = $Url
    namespace   = $Namespace
    require     = @{
        'blessing-skin-server' = '^5.0.0'
    }
    enchants    = @{
        icon = @{
            fa = ''
            bg = ''
        }
    }
}

Set-Location ./plugins
New-Item "./$Id/src" -ItemType Directory | Out-Null
New-Item "./$Id/lang/en" -ItemType Directory | Out-Null
New-Item "./$Id/lang/zh_CN" -ItemType Directory | Out-Null
ConvertTo-Json $manifest | Set-Content "./$Id/package.json"
Set-Content -Value "<?php`n`nreturn function () {};" -Path "./$Id/bootstrap.php"
Set-Content -Value "title: `ndescription: " -Path "./$Id/lang/en/general.yml"
Set-Content -Value "title: `ndescription: " -Path "./$Id/lang/zh_CN/general.yml"

if ($View) {
    New-Item "./$Id/views" -ItemType Directory | Out-Null
}

if ($Asset) {
    New-Item "./$Id/assets" -ItemType Directory | Out-Null
}

if ($Lang) {
    New-Item "./$Id/lang" -ItemType Directory | Out-Null
}

Set-Location ..
