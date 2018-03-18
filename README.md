# _Gallery

[![Build Status](https://travis-ci.org/miya0001/_gallery.svg?branch=master)](https://travis-ci.org/miya0001/_gallery)

This plugin will replace WordPress `[gallery]` shortcode to masonry layout.

![](https://www.evernote.com/l/ABUOZuavR05PbaoE2ICRvDS6LYbONZ8FVPgB/image.png)

## Features

* A lightweight, no-dependency.
* CSS only, no JS.
* Image only, no caption.

So this plugin is very simple.

## Sample Output

```
<div class="underscore-gallery" style="column-count: 5; -moz-column-count: 5;">
	<figure class="underscore-gallery-item">
		<a href="...">
			<img width="165" height="210" ...>
		</a>
	</figure>
	<figure class="underscore-gallery-item">
		<a href="...">
			<img width="165" height="210" ...>
		</a>
	</figure>
</div>
```

## Customizing

If you want to change number of columns for mobile or tablet. Please put styles like following in your theme.

```
@media screen and ( max-width: 768px )
{
	.underscore-gallery
	{
		column-count: 3 !important;
		-moz-column-count: 3 !important;
	}
}
```
