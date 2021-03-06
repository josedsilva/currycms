<?php
/**
 * Curry CMS
 *
 * LICENSE
 *
 * This source file is subject to the GPL license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://currycms.com/license
 *
 * @category   Curry CMS
 * @package    Curry
 * @copyright  2011-2012 Bombayworks AB (http://bombayworks.se)
 * @license    http://currycms.com/license GPL
 * @link       http://currycms.com
 */

namespace Curry\Controller;
use Curry\App;
use Curry\Generator\AbstractGenerator;
use Symfony\Component\HttpFoundation\Request;

class Page {
    /**
     * @var App
     */
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function __invoke(Request $request, \Page $page)
    {
        $app = $this->app;
        $pageRevision = $page->getPageRevision();

        // @todo: these are currently unused :S
        $vars = array();
        $options = array();

        // Find language
        $language = $page->getInheritedProperty('Language');
        $fallbackLanguage = $app['fallbackLanguage'];
        if(!$language && $fallbackLanguage) {
            $app->logger->debug('Using fallback language');
            $language = $fallbackLanguage;
        }

        // Set language
        if ($language) {
            $locale = \Curry_Language::setLanguage($language);
            $language = \Curry_Language::getLanguage();
            if($language)
                $app->logger->debug('Current language is now '.$language->getName().' (with locale '.$locale.')');
        } else {
            $app->logger->debug('Language not set for page');
        }

        // Attempt to render page
        $app->logger->debug('Showing page ' . $page->getName() . ' (PageRevisionId: '.$pageRevision->getPageRevisionId().')');
        $generator = AbstractGenerator::create($app, $pageRevision);

        return $generator->render($vars, $options);
    }
}
