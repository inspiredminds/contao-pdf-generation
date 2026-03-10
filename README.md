[![](https://img.shields.io/packagist/v/inspiredminds/contao-pdf-generation.svg)](https://packagist.org/packages/inspiredminds/contao-pdf-generation)
[![](https://img.shields.io/packagist/dt/inspiredminds/contao-pdf-generation.svg)](https://packagist.org/packages/inspiredminds/contao-pdf-generation)

Contao PDF Generation
=====================

This allows generating PDF files from regular Contao pages via the [mPDF](https://mpdf.github.io/) PDF generator.

## Usage

First you need to create a PDF generation configuration. If you only need the default settings, the following would
suffice for example:

```yaml
contao_pdf_generation:
    configurations:
        my_pdf_config: ~
```

To use a PDF template, you can define the absolute path to the template like this for example:

```yaml
contao_pdf_generation:
    configurations:
        my_pdf_config:
            pdf_template: '%kernel.project_dir%/files/pdf/my_pdf_template.pdf'
```

To integrate a custom font, you also need to define the absolute path to the font files:

```yaml
contao_pdf_generation:
    configurations:
        my_pdf_config:
            fonts:
                default_font: foobar
                custom_fonts:
                    foobar:
                        R: '%kernel.project_dir%/files/pdf/Foobar-Regular.ttf'
                        B: '%kernel.project_dir%/files/pdf/Foobar-Bold.ttf'
```

check the output of `config:dump-reference contao_pdf_generation` for more options.

Next you will have to select a PDF generation configuration in your Contao page layout. You can find this in the
_Expert settings_ tab of your layout under **PDF generation configuration**. Only pages with a valid PDF generation
configuration layout will be able to actually generate a PDF.

Next you will have to create a _Generate PDF_ content element. This element will output a form with a submit button that
will trigger the PDF generation for the current page. Insert this element then either in your layout, or directly in
your content somewhere.

> [!NOTE]
> If you need support for default layouts, install version **1.x** of this extension.
