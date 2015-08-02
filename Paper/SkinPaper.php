<?php
if ( !defined( 'MEDIAWIKI' ) ) {
        die( -1 );
}

class SkinPaper extends SkinTemplate {

        protected static $bodyClasses = array( 'paper-animateLayout' );

        var $skinname = 'paper', $stylename = 'paper',
                $template = 'PaperTemplate', $useHeadElement = true;

        /**
         * Initializes output page and sets up skin-specific parameters
         * @param $out OutputPage object to initialize
         */
        public function initPage( OutputPage $out ) {
                global $wgLocalStylePath;

                $out->addMeta( 'viewport', 'width=device-width' );

                parent::initPage( $out );

                // Append CSS which includes IE only behavior fixes for hover support -
                // this is better than including this in a CSS file since it doesn't
                // wait for the CSS file to load before fetching the HTC file.
                $min = $this->getRequest()->getFuzzyBool( 'debug' ) ? '' : '.min';
/*              $out->addHeadItem( 'csshover',
                        '<!--[if lt IE 7]><style type="text/css">body{behavior:url("' .
                                htmlspecialchars( $wgLocalStylePath ) .
                                "/{$this->stylename}/csshover{$min}.htc\")}</style><![endif]-->"
                );*/
                //$out->addHeadItem( 'csshover', '<link rel="stylesheet" href="//enhawiki.kr../skins/moniwiki/theme/enha/css/default.css?v=1541703"/>'  );
                $out->addModules( array( 'skins.paper.js', 'skins.paper.collapsibleNav' ) );
        }

        /**
         * Loads skin and user CSS files.
         * @param $out OutputPage object
         */
        function setupSkinUserCss( OutputPage $out ) {
                parent::setupSkinUserCss( $out );

                $styles = array( 'mediawiki.skinning.interface', 'skins.paper.styles' );
                wfRunHooks( 'SkinPaperStyleModules', array( $this, &$styles ) );
                $out->addModuleStyles( $styles );
        }

        /**
         * Adds classes to the body element.
         *
         * @param $out OutputPage object
         * @param &$bodyAttrs Array of attributes that will be set on the body element
         */
        function addToBodyAttributes( $out, &$bodyAttrs ) {
                if ( isset( $bodyAttrs['class'] ) && strlen( $bodyAttrs['class'] ) > 0 ) {
                        $bodyAttrs['class'] .= ' ' . implode( ' ', static::$bodyClasses );
                } else {
                        $bodyAttrs['class'] = implode( ' ', static::$bodyClasses );
                }
        }
}
?>
