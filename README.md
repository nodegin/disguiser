# disguiser
a simple html class name obfuscate + html minify php class

# usage
move to index.php

# example outputs
**disguiser disabled**
```html

<html>
<head>
<title>hey world!</title>
<style>body{color:white;text-align:center;}.my-extremely-unbelievably-very-long-class-name{background:red;}.X_compare_with_this-long_long_longer-my-extremely-unbelievably-very-long-class-name{background:blue;}</style>
</head>
<body>
<div class="my-extremely-unbelievably-very-long-class-name">foo</div>
<div class="X_compare_with_this-long_long_longer-my-extremely-unbelievably-very-long-class-name">bar</div>
</body>
</html>
```

**disguiser enabled, minify disabled**
```html

<html>
<head>
<title>hey world!</title>
<style>body{color:white;text-align:center;}.my-extremely-unbelievably-very-long-class-name{background:red;}.k-l-d-e-g-f-h-i-j{background:blue;}</style>
</head>
<body>
<div class="my-extremely-unbelievably-very-long-class-name">foo</div>
<div class="k-l-d-e-g-f-h-i-j">bar</div>
</body>
</html>
```

**all enabled**
```html
<html><head><title>hey world!</title><style>body{color:white;text-align:center;}.my-extremely-unbelievably-very-long-class-name{background:red;}.k-l-d-e-g-f-h-i-j{background:blue;}</style></head><body><div class="my-extremely-unbelievably-very-long-class-name">foo</div><div class="k-l-d-e-g-f-h-i-j">bar</div></body></html>
```

# renaming
Classes to be renamed must starts with a prefix

You should name the prefix only using `[a-zA-Z0-9_]` and below 2 rules

1. Starts with alphabet
2. Ends with underscore

Example: `DisGuiseMe_`, `Minify_`, `hideMe_`, etc.

However, you are not restricted by above limitation

If you wish, you can name anything as prefix, but may cause CSS bug
