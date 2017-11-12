Installer
=

<img src="https://chevereto.com/src/img/installer/screen.jpg?20171112">

## About this repo

This installs Chevereto (free or paid) in seconds by using your server to download and extract all the files needed.

## How to use it

1. Download the [index.php](https://chevereto.com/download/file/installer) file
2. Upload this file to your target destination (usually the `public_html` folder)
3. Open your website target destination URL and follow the install process

## API

REST API is methods are fully supported by this installer so you can access all functions programmatically direcly from your application. This API returns in JSON format.

### Requests
All API request must specify the `action` and `edition` parameters. Available actions are `download` and `extract`. Available editions are `free` and `paid`. The API accepts both `POST` (recommended) and `GET` requests.

#### Download
```
/?action=download&edition=free // Chevereto Free
/?action=download&edition=paid&license=<license_key> // Chevereto (paid)
```
Downloads the target `edition` package in the installer directory. The `license` key parameter is only required for the paid edition.

##### Response (success)
```
{  
   "status":{  
      "code":200,
      "description":"OK"
   },
   "response":{  
      "code":200,
      "message":"Downloaded chevereto-pkg-bbf9ab00.zip (4.4 MB @6.27MB\/s.)"
   },
   "request":{  
      "action":"download",
      "edition":"free"
   },
   "data":{  
      "download":{  
         "fileBasename":"chevereto-pkg-bbf9ab00.zip"
      }
   }
}
```

#### Extract
```
/?action=extract&edition=<edition>&fileBasename=<download_basename>
```
Extracts the previously downloaded package and removes the package file. `<download_basename>` is the basename of the previously downloaded file (the one returned by `action=download`). This basename should be something like `chevereto-pkg-hUi9eyNc.zip`, please note that this file name is randomly generated.

##### Response (success)
```
{  
   "status":{  
      "code":200,
      "description":"OK"
   },
   "response":{  
      "code":200,
      "message":"Extraction completeted (414 files in 0.19s)"
   },
   "request":{  
      "action":"extract",
      "edition":"free",
      "fileBasename":"chevereto-pkg-bbf9ab00.zip"
   }
}
```
