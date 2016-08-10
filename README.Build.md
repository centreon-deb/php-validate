Build instructions for the impatient


```bash
curl https://raw.githubusercontent.com/centreon-deb/php-validate/debian/bootstrap | sh
cd php-validate && git deb-pkg -C -U -u 0.8.5 -d origin/debian build
```

Further instruction in the [README.Build.md](README.Build.md) file.
