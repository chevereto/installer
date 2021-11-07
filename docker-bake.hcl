target "docker-metadata-action" {}

target "build" {
  inherits = ["docker-metadata-action"]
  context = "./"
  dockerfile = "httpd-php-installer.Dockerfile"
  platforms = [
    "linux/amd64",
    "linux/arm64",
  ]
}
