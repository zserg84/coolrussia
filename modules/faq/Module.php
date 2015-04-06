<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 13.02.15
 * Time: 10:29
 */

namespace modules\faq;

use modules\translations\components\DbMessageSource;

class Module extends \modules\base\components\Module
{
    public static $name = 'faq';

    /**
     * @var string Preview path
     */
    public $previewPath = '@statics/web/faq/previews/';

    /**
     * @var string Image path
     */
    public $imagePath = '@statics/web/faq/images/';

    /**
     * @var string Files path
     */
    public $filePath = '@statics/web/faq/files';

    /**
     * @var string Files path
     */
    public $contentPath = '@statics/web/faq/content';

    /**
     * @var string Images temporary path
     */
    public $imagesTempPath = '@statics/temp/faq/images/';

    /**
     * @var string Preview URL
     */
    public $previewUrl = '/statics/faq/previews';

    /**
     * @var string Image URL
     */
    public $imageUrl = '/statics/faq/images';

    /**
     * @var string Files URL
     */
    public $fileUrl = '/statics/faq/files';

    /**
     * @var string Files URL
     */
    public $contentUrl = '/statics/faq/content';

} 