####gitbook 使用教程

1、安装
```
brew install nodejs

npm install -g gitbook-cli
```

2、查看命令

```
 ~ gitbook --help

  Usage: gitbook [options] [command]


  Options:

    -v, --gitbook [version]  specify GitBook version to use
    -d, --debug              enable verbose error
    -V, --version            Display running versions of gitbook and gitbook-cli
    -h, --help               output usage information


  Commands:

    ls                        List versions installed locally
    current                   Display currently activated version
    ls-remote                 List remote versions available for install
    fetch [version]           Download and install a <version>
    alias [folder] [version]  Set an alias named <version> pointing to <folder>
    uninstall [version]       Uninstall a version
    update [tag]              Update to the latest version of GitBook
    help                      List commands for GitBook
    *                         run a command with a specific gitbook version
```

3、gitbook 导出PDF

此处需要安装Calibre
```
http://calibre-ebook.com/download_osx
ln -s /Applications/calibre.app/Contents/MacOS/ebook-convert /usr/local/bin
```

4、生成pdf 
```
gitbook pdf . test.pdf  //当前目录生成名为test.pdf 的文件
```
