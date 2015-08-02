<?php
/**
 * NewiWiki - Vector 기반의 iWiki스킨
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @todo document
 * @file
 * @ingroup Skins
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( -1 );
}

/**
 * SkinTemplate class for Vector skin
 * @ingroup Skins
 */
class SkinNewiwiki extends SkinTemplate {

	protected static $bodyClasses = array( 'newiwiki-animateLayout' );

	var $skinname = 'newiwiki', $stylename = 'newiwiki',
		$template = 'NewiwikiTemplate', $useHeadElement = true;

	function tocList($toc) { 
		$this->savedTOC = parent::tocList($toc); 
		return ""; 
	} 

	/**
	 * Initializes output page and sets up skin-specific parameters
	 * @param $out OutputPage object to initialize
	 */
	public function initPage( OutputPage $out ) {
		global $wgLocalStylePath;

		parent::initPage( $out );

		// Append CSS which includes IE only behavior fixes for hover support -
		// this is better than including this in a CSS file since it doesn't
		// wait for the CSS file to load before fetching the HTC file.
		$min = $this->getRequest()->getFuzzyBool( 'debug' ) ? '' : '.min';
/*		$out->addHeadItem( 'csshover',
			'<!--[if lt IE 7]><style type="text/css">body{behavior:url("' .
				htmlspecialchars( $wgLocalStylePath ) .
				"/{$this->stylename}/csshover{$min}.htc\")}</style><![endif]-->"
		);*/
		$out->addScriptFile('https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js');
		$out->addScriptFile( '../newiwiki/bootstrap/js/bootstrap.min.js' );
		$out->addScriptFile( '../newiwiki/scroll.js?708' );
		$out->addScriptFile( '../newiwiki/tocmove.js?700' );
		$out->addScriptFile( '../newiwiki/alertmsg.js?700' );
		$out->addScriptFile( '../newiwiki/jquery.cookie.js?700' );
        $out->addMeta( 'viewport', 'width=device-width, initial-scale=1, maximum-scale=1' );
		$out->addModules( array( 'skins.newiwiki.js', 'skins.newiwiki.collapsibleNav' ) );
	}

	/**
	 * Loads skin and user CSS files.
	 * @param $out OutputPage object
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );

		$styles = array( 'mediawiki.skinning.interface', 'skins.newiwiki.styles' );
		wfRunHooks( 'SkinNewiWikiStyleModules', array( $this, &$styles ) );
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

/**
 * QuickTemplate class for Vector skin
 * @ingroup Skins
 */
class NewiwikiTemplate extends BaseTemplate {

	/* Functions */

	/**
	 * Outputs the entire contents of the (X)HTML page
	 */
	public function execute() {
		global $wgVectorUseIconWatch, $wgUser;

		// Build additional attributes for navigation urls
		$nav = $this->data['content_navigation'];

		if ( $wgVectorUseIconWatch ) {
			$mode = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() ) ? 'unwatch' : 'watch';
			if ( isset( $nav['actions'][$mode] ) ) {
				$nav['views'][$mode] = $nav['actions'][$mode];
				$nav['views'][$mode]['class'] = rtrim( 'icon ' . $nav['views'][$mode]['class'], ' ' );
				$nav['views'][$mode]['primary'] = true;
				unset( $nav['actions'][$mode] );
			}
		}

		$xmlID = '';
		foreach ( $nav as $section => $links ) {
			foreach ( $links as $key => $link ) {
				if ( $section == 'views' && !( isset( $link['primary'] ) && $link['primary'] ) ) {
					$link['class'] = rtrim( 'collapsible ' . $link['class'], ' ' );
				}

				$xmlID = isset( $link['id'] ) ? $link['id'] : 'ca-' . $xmlID;
				$nav[$section][$key]['attributes'] =
					' id="' . Sanitizer::escapeId( $xmlID ) . '"';
				if ( $link['class'] ) {
					$nav[$section][$key]['attributes'] .=
						' class="' . htmlspecialchars( $link['class'] ) . '"';
					unset( $nav[$section][$key]['class'] );
				}
				if ( isset( $link['tooltiponly'] ) && $link['tooltiponly'] ) {
					$nav[$section][$key]['key'] =
						Linker::tooltip( $xmlID );
				} else {
					$nav[$section][$key]['key'] =
						Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( $xmlID ) );
				}
			}
		}
		$this->data['namespace_urls'] = $nav['namespaces'];
		$this->data['view_urls'] = $nav['views'];
		$this->data['action_urls'] = $nav['actions'];
		$this->data['variant_urls'] = $nav['variants'];

		// Reverse horizontally rendered navigation elements
		if ( $this->data['rtl'] ) {
			$this->data['view_urls'] =
				array_reverse( $this->data['view_urls'] );
			$this->data['namespace_urls'] =
				array_reverse( $this->data['namespace_urls'] );
			$this->data['personal_urls'] =
				array_reverse( $this->data['personal_urls'] );
		}
		
		$userLinks = $this->getPersonalTools();
        $user = ( $wgUser->isLoggedIn() ) ? array_shift($userLinks) : array_pop($userLinks);
        $userLink = $user['links'][0];

		// Output HTML Page
		$this->html( 'headelement' );
?>

		<div id="mw-page-base" class="noprint"></div>
		<div id="mw-head-base" class="noprint"></div>
		<div id="body_wrap" class="container-fluid">
			<div class="top_navi">
				<div class="navbar navbar-iWiki navbar-fixed-top">
			        <div class="container-fluid">
        			    <div class="navbar-header">
		    	            <button type="button" class="navbar-toggle navbar-left collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			    	            <span class="sr-only">Toggle navigation</span>
		    	    	        <span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>
		                	</button>
			                <form class="navbar-form navbar-right navbar-small-form" action="<?php $this->text( 'wgScript' ) ?>">
			                    <input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
			                    <?php echo $this->makeSearchInput( array( "id" => "searchInput", "class" => "form-control", "placeholder" => "검색어") ); ?>
            	            </form>
		        	        <button type="button" class="navbar-toggle navbar-toogle-right collapsed" data-toggle="collapse" <?php
			        	        if ( $wgUser->isLoggedIn() ) {
				                    ?>data-target="#personalbar" <?php
			            	    } else {
			        	            ?>onclick="location.href='<?php echo $userLink['href']; ?>'"<?php
        				        }
			    	            ?>aria-expanded="false" aria-controls="personalbar">
    	            	        <span class="sr-only">Toggle navigation</span>
	                    	    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
	                        </button>
			                <a class="navbar-brand" href="/wiki/FrontPage"><?php $this->msg( 'tagline' ) ?></a>
        			    </div>
	        	    <div id="navbar" class="navbar-collapse collapse">
    	        	    <ul class="nav navbar-nav navbar-left">
	                	    <?php
    	                	$this->customBox('navigaion', $this->data['sidebar']['navigation']);
	        	            ?>
    	        	    </ul>
	    	            <?php
    	    	        if ( $wgUser->isLoggedIn() ) {
                	    ?>
        	        	    <button type="button" class="btn btn-success loginbtn navbar-right" data-toggle="collapse" href="#personalbar" aria-expanded="false" aria-controls="personalbar">
		                    <?=$wgUser->getName();?>
    		                </a>
	    	            <?php
    	    	        } else {
                	        echo Linker::linkKnown( SpecialPage::getTitleFor( 'Userlogin' ), wfMsg( 'login' ), array( 'class' => 'navbar-right btn btn-warning loginbtn ') );
    	            	}
		                ?>	
		            </div>
    		        <div id="personalbar" class="navbar-personal collapse">
						<ul class="nav nav-stacked navbar-personalbar">
							<?php $this->renderNavigation( 'PERSONAL' ); ?>
						</ul>
	        	    </div>
				</div>
	        </div>
	    </div>
			<div class="row main_content">
				<div class="col-xs-9">
		<div id="content" class="mw-body" role="main">
			<a id="top"></a>
			<div class="top_menu">
        <ul>
            <?php
            $title = $this->getSkin()->getTitle();
            if ( $title->getNamespace() != NS_SPECIAL ) {
                $companionTitle = $title->isTalkPage() ? $title->getSubjectPage() : $title->getTalkPage();
            ?>
            <li><?php echo Linker::linkKnown( $title, '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 편집', null, array( 'action' => 'edit' ) ); ?></li>
            <li><?php echo Linker::linkKnown( $title, '<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span> 새문단', null, array( 'action' => 'edit', 'section' => 'new' ) ); ?></li>
            <li><?php echo Linker::linkKnown( $title, '<span class="glyphicon glyphicon-time" aria-hidden="true"></span> 기록', null, array( 'action' => 'history' ) ); ?></li>
            <?php
            if ( $companionTitle ) { 
				if ($title->getNamespace() == NS_TALK) {
					?>
					<li><?php echo Linker::linkKnown( $companionTitle, '<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> 본문' ); ?></li>
					<?php
				} else {
				?>
	                <li><?php echo Linker::linkKnown( $companionTitle, '<span class="glyphicon glyphicon-fire" aria-hidden="true"></span> 토론', null ); ?></li>
    		        <?php
				}
            } ?>
            <?php
            $mode = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() ) ? 'unwatch' : 'watch';
            if ($mode != 'watch') {
                $watchname = '주시해제';
            } else {
                $watchname = '주시';
                $emptystar = '-empty';
            }
			if ( $title->getNamespace() != NS_SPECIAL ) {
            ?>
            <li><?php echo Linker::linkKnown( $title, '<span class="glyphicon glyphicon-star' . $emptystar . '" aria-hidden="true"></span> ' . $watchname, null, array( 'action' => $mode ) ); ?></li>
			<?php
			}
			?>
            <li><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'Movepage', $title ), '<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> 옮기기' ); ?></li>
            <?php if ( $title->quickUserCan( 'protect', $user ) ) { ?>
            <li><?php echo Linker::linkKnown( $title, '<span class="glyphicon glyphicon-lock" aria-hidden="true"></span> 보호', null, array( 'action' => 'protect' ) ); ?></li>
            <?php } ?>
            <?php if ( $title->quickUserCan( 'delete', $user ) ) {
            ?>
            <li><?php echo Linker::linkKnown( $title, '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 삭제', null, array( 'action' => 'delete' ) ); ?></li>
            <?php }
			}
            ?>
        </ul>
    </div>
			<div id="mw-js-message" style="display:none;"<?php $this->html( 'userlangattributes' ) ?>></div>
			<?php if ( $this->data['sitenotice'] ) { ?>
			<div id="alertmsg" class="alert alert-info siteNotice alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<?php $this->html( 'sitenotice' ) ?>
			</div>
			<?php } ?>
			<h1 id="firstHeading" class="firstHeading" lang="<?php
				$this->data['pageLanguage'] = $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();
				$this->text( 'pageLanguage' );
			?>"><span dir="auto"><?php $this->html( 'title' ) ?></span></h1>
			<?php $this->html( 'prebodyhtml' ) ?>
			<div id="bodyContent">
				<?php if ( $this->data['isarticle'] ) { ?>
				<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>
				<?php } ?>
				<div id="contentSub"<?php $this->html( 'userlangattributes' ) ?>><?php $this->html( 'subtitle' ) ?></div>
				<?php if ( $this->data['undelete'] ) { ?>
				<div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
				<?php } ?>
				<?php if ( $this->data['newtalk'] ) { ?>
				<div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
				<?php } ?>
				<div id="jump-to-nav" class="mw-jump">
					<?php $this->msg( 'jumpto' ) ?>
					<a href="#mw-navigation"><?php $this->msg( 'jumptonavigation' ) ?></a><?php $this->msg( 'comma-separator' ) ?>
					<a href="#p-search"><?php $this->msg( 'jumptosearch' ) ?></a>
				</div>
				<?php $this->html( 'bodycontent' ) ?>
				<?php if ( $this->data['printfooter'] ) { ?>
				<div class="printfooter">
				<?php $this->html( 'printfooter' ); ?>
				</div>
				<?php } ?>
				<?php if ( $this->data['catlinks'] ) { ?>
				<?php $this->html( 'catlinks' ); ?>
				<?php } ?>
				<?php if ( $this->data['dataAfterContent'] ) { ?>
				<?php $this->html( 'dataAfterContent' ); ?>
				<?php } ?>
				<div class="visualClear"></div>
				<?php $this->html( 'debughtml' ); ?>
			</div>
		</div>
		<div id="footer" role="contentinfo"<?php $this->html( 'userlangattributes' ) ?>>
			<?php foreach ( $this->getFooterLinks() as $category => $links ) { ?>
				<ul id="footer-<?php echo $category ?>">
					<?php foreach ( $links as $link ) { ?>
						<li id="footer-<?php echo $category ?>-<?php echo $link ?>"><?php $this->html( $link ) ?></li>
					<?php } ?>
				</ul>
			<?php } ?>
			<?php $footericons = $this->getFooterIcons( "icononly" );
			if ( count( $footericons ) > 0 ) { ?>
				<ul id="footer-icons">
<?php			foreach ( $footericons as $blockName => $footerIcons ) { ?>
					<li id="footer-<?php echo htmlspecialchars( $blockName ); ?>ico">
<?php				foreach ( $footerIcons as $icon ) { ?>
						<?php echo $this->getSkin()->makeFooterIcon( $icon ); ?>

<?php				} ?>
					</li>
<?php			} ?>
				</ul>
			<?php } ?>
			<div style="clear:both"></div>
				</div>
				</div>
				<div class="col-xs-3">
					<div class="right_col">
						<form class="right_search" action="<?php $this->text( 'wgScript' ) ?>">
		                    <input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
    	                    <?php echo $this->makeSearchInput( array( "id"=>"searchInput","class" => "form-control", "placeholder" => "검색어") ); ?>
        	            </form>
						<div id="right_toc">
						</div>
						<div id = "recent-list-div">
							<div id="recent-title">
								<h2>최근 바뀜
									<span class="recent-more">
										<span class="mw-editsection-bracket">[</span>
										<a href="/wiki/특수:최근바뀜" title="최근바뀜문서">more</a>
										<span class="mw-editsection-bracket">]</span>
									</span>
								</h2>
							</div>
							<ul id = "recent-list">
							</ul>
						</div>
					</div>
            	</div>
			</div>
		</div>
		<div class="top_scroll">
			<a href="#" class="top_s_btn">
				<span class="glyphicon glyphicon-open"></span>
			</a>
		</div>
		<?php $this->printTrail(); ?>

	</body>
</html>
<?php
	}
	
	function customBox( $bar, $cont ) {
        $msgObj = wfMessage( $bar );
        if ( is_array( $cont ) ) {
            foreach ( $cont as $key => $val ) {
                echo $this->makeListItem( $key, $val );
            }
        } else {
            print $cont;
        }
        $this->renderAfterPortlet( $bar );
    }

	private function nav( $nav ) {
        $output = '';
        foreach ( $nav as $topItem ) {
            $pageTitle = Title::newFromText( $topItem['link'] ?: $topItem['title'] );
            if ( array_key_exists( 'sublinks', $topItem ) ) {
                foreach ( $topItem['sublinks'] as $subLink ) {
                    if ( 'divider' == $subLink ) {
                        $output .= "<li class='divider'></li>\n";
                    } elseif ( $subLink['textonly'] ) {
                        $output .= "<li class='nav-header'>{$subLink['title']}</li>\n";
                    } else {
                        if( $subLink['local'] && $pageTitle = Title::newFromText( $subLink['link'] ) ) {
                            $href = $pageTitle->getLocalURL();
                        } else {
                            $href = $subLink['link'];
                        }//end else
                        $slug = strtolower( str_replace(' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', trim( strip_tags( $subLink['title'] ) ) ) ) );
                        $output .= "<li {$subLink['attributes']}><a href='{$href}' class='{$subLink['class']} {$slug}'>{$subLink['title']}</a>";
                    }//end else
                }
            } else {
                if( $pageTitle ) {
                    $output .= '<li' . ($this->data['title'] == $topItem['title'] ? ' class="active"' : '') . '><a href="' . ( $topItem['external'] ? $topItem['link'] : $pageTitle->getLocalURL() ) . '">' . $topItem['title'] . '</a></li>';
                }//end if
            }//end else
        }//end foreach
        return $output;
    }

	function get_array_links( $array, $title, $which ) {
        $nav = array();
        $nav[] = array('title' => $title );
        foreach( $array as $key => $item ) {
            $link = array(
                'id' => Sanitizer::escapeId( $key ),
                'attributes' => $item['attributes'],
                'link' => htmlspecialchars( $item['href'] ),
                'key' => $item['key'],
                'class' => htmlspecialchars( $item['class'] ),
                'title' => htmlspecialchars( $item['text'] ),
            );
            if( 'page' == $which ) {
                switch( $link['title'] ) {
                case 'Page': $icon = 'file'; break;
                case 'Discussion': $icon = 'comment'; break;
                case 'Edit': $icon = 'pencil'; break;
                case 'History': $icon = 'clock-o'; break;
                case 'Delete': $icon = 'remove'; break;
                case 'Move': $icon = 'arrows'; break;
                case 'Protect': $icon = 'lock'; break;
                case 'Watch': $icon = 'eye-open'; break;
                case 'Unwatch': $icon = 'eye-slash'; break;
                }//end switch
                $link['title'] = '<i class="fa fa-' . $icon . '"></i> ' . $link['title'];
            } elseif( 'user' == $which ) {
                switch( $link['title'] ) {
                case 'My talk': $icon = 'comment'; break;
                case 'My preferences': $icon = 'cog'; break;
                case 'My watchlist': $icon = 'eye-close'; break;
                case 'My contributions': $icon = 'list-alt'; break;
                case 'Log out': $icon = 'off'; break;
                default: $icon = 'user'; break;
                }//end switch
                $link['title'] = '<i class="fa fa-' . $icon . '"></i> ' . $link['title'];
            }//end elseif
            $nav[0]['sublinks'][] = $link;
        }//end foreach
        return $this->nav( $nav );
    }

	/**
	 * Render a series of portals
	 *
	 * @param $portals array
	 */
	protected function renderPortals( $portals ) {
		// Force the rendering of the following portals
		if ( !isset( $portals['SEARCH'] ) ) {
			$portals['SEARCH'] = true;
		}
		if ( !isset( $portals['TOOLBOX'] ) ) {
			$portals['TOOLBOX'] = true;
		}
		if ( !isset( $portals['LANGUAGES'] ) ) {
			$portals['LANGUAGES'] = true;
		}
		// Render portals
		foreach ( $portals as $name => $content ) {
			if ( $content === false ) {
				continue;
			}

			switch ( $name ) {
				case 'SEARCH':
					break;
				case 'TOOLBOX':
					$this->renderPortal( 'tb', $this->getToolbox(), 'toolbox', 'SkinTemplateToolboxEnd' );
					break;
				case 'LANGUAGES':
					if ( $this->data['language_urls'] !== false ) {
						$this->renderPortal( 'lang', $this->data['language_urls'], 'otherlanguages' );
					}
					break;
				default:
					$this->renderPortal( $name, $content );
				break;
			}
		}
	}

	/**
	 * @param $name string
	 * @param $content array
	 * @param $msg null|string
	 * @param $hook null|string|array
	 */
	protected function renderPortal( $name, $content, $msg = null, $hook = null ) {
		if ( $msg === null ) {
			$msg = $name;
		}
		$msgObj = wfMessage( $msg );
		?>
<div class="portal" role="navigation" id='<?php echo Sanitizer::escapeId( "p-$name" ) ?>'<?php echo Linker::tooltip( 'p-' . $name ) ?> aria-labelledby='<?php echo Sanitizer::escapeId( "p-$name-label" ) ?>'>
	<h3<?php $this->html( 'userlangattributes' ) ?> id='<?php echo Sanitizer::escapeId( "p-$name-label" ) ?>'><?php echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg ); ?></h3>
	<div class="body">
<?php
		if ( is_array( $content ) ) { ?>
		<ul>
<?php
			foreach ( $content as $key => $val ) { ?>
			<?php echo $this->makeListItem( $key, $val ); ?>

<?php
			}
			if ( $hook !== null ) {
				wfRunHooks( $hook, array( &$this, true ) );
			}
			?>
		</ul>
<?php
		} else { ?>
		<?php
			echo $content; /* Allow raw HTML block to be defined by extensions */
		}

		$this->renderAfterPortlet( $name );
		?>
	</div>
</div>
<?php
	}

	/**
	 * Render one or more navigations elements by name, automatically reveresed
	 * when UI is in RTL mode
	 *
	 * @param $elements array
	 */
	protected function renderNavigation( $elements ) {
		global $wgVectorUseSimpleSearch;

		// If only one element was given, wrap it in an array, allowing more
		// flexible arguments
		if ( !is_array( $elements ) ) {
			$elements = array( $elements );
		// If there's a series of elements, reverse them when in RTL mode
		} elseif ( $this->data['rtl'] ) {
			$elements = array_reverse( $elements );
		}
		// Render elements
		foreach ( $elements as $name => $element ) {
			switch ( $element ) {
				case 'NAMESPACES':
?>
<div id="p-namespaces" role="navigation" class="vectorTabs<?php if ( count( $this->data['namespace_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-namespaces-label">
	<h3 id="p-namespaces-label"><?php $this->msg( 'namespaces' ) ?></h3>
	<ul<?php $this->html( 'userlangattributes' ) ?>>
		<?php foreach ( $this->data['namespace_urls'] as $link ) { ?>
			<li <?php echo $link['attributes'] ?>><span><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></span></li>
		<?php } ?>
	</ul>
</div>
<?php
				break;
				case 'VARIANTS':
?>
<div id="p-variants" role="navigation" class="vectorMenu<?php if ( count( $this->data['variant_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-variants-label">
	<h3 id="mw-vector-current-variant">
	<?php foreach ( $this->data['variant_urls'] as $link ) { ?>
		<?php if ( stripos( $link['attributes'], 'selected' ) !== false ) { ?>
			<?php echo htmlspecialchars( $link['text'] ) ?>
		<?php } ?>
	<?php } ?>
	</h3>
	<h3 id="p-variants-label"><span><?php $this->msg( 'variants' ) ?></span><a href="#"></a></h3>
	<div class="menu">
		<ul>
			<?php foreach ( $this->data['variant_urls'] as $link ) { ?>
				<li<?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" lang="<?php echo htmlspecialchars( $link['lang'] ) ?>" hreflang="<?php echo htmlspecialchars( $link['hreflang'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<?php
				break;
				case 'VIEWS':
?>
<div id="p-views" role="navigation" class="vectorTabs<?php if ( count( $this->data['view_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-views-label">
	<h3 id="p-views-label"><?php $this->msg( 'views' ) ?></h3>
	<ul<?php $this->html( 'userlangattributes' ) ?>>
		<?php foreach ( $this->data['view_urls'] as $link ) { ?>
			<li<?php echo $link['attributes'] ?>><span><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php
				// $link['text'] can be undefined - bug 27764
				if ( array_key_exists( 'text', $link ) ) {
					echo array_key_exists( 'img', $link ) ? '<img src="' . $link['img'] . '" alt="' . $link['text'] . '" />' : htmlspecialchars( $link['text'] );
				}
				?></a></span></li>
		<?php } ?>
	</ul>
</div>
<?php
				break;
				case 'ACTIONS':
?>
<div id="p-cactions" role="navigation" class="vectorMenu<?php if ( count( $this->data['action_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-cactions-label">
	<h3 id="p-cactions-label"><span><?php $this->msg( 'actions' ) ?></span><a href="#"></a></h3>
	<div class="menu">
		<ul<?php $this->html( 'userlangattributes' ) ?>>
			<?php foreach ( $this->data['action_urls'] as $link ) { ?>
				<li<?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<?php
				break;
				case 'PERSONAL':
					$personalTools = $this->getPersonalTools();
					foreach ( $personalTools as $key => $item ) {
						echo $this->makeListItem( $key, $item );
					}
				break;
				case 'SEARCH':
?>
<div id="p-search" role="search">
	<h3<?php $this->html( 'userlangattributes' ) ?>><label for="searchInput"><?php $this->msg( 'search' ) ?></label></h3>
	<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
		<?php if ( $wgVectorUseSimpleSearch ) { ?>
			<div id="simpleSearch">
		<?php } else { ?>
			<div>
		<?php } ?>
			<?php
			echo $this->makeSearchInput( array( 'id' => 'searchInput' ) );
			echo Html::hidden( 'title', $this->get( 'searchtitle' ) );
			// We construct two buttons (for 'go' and 'fulltext' search modes), but only one will be
			// visible and actionable at a time (they are overlaid on top of each other in CSS).
			// * Browsers will use the 'fulltext' one by default (as it's the first in tree-order), which
			//   is desirable when they are unable to show search suggestions (either due to being broken
			//   or having JavaScript turned off).
			// * The mediawiki.searchSuggest module, after doing tests for the broken browsers, removes
			//   the 'fulltext' button and handles 'fulltext' search itself; this will reveal the 'go'
			//   button and cause it to be used.
			echo $this->makeSearchButton( 'fulltext', array( 'id' => 'mw-searchButton', 'class' => 'searchButton mw-fallbackSearchButton' ) );
			echo $this->makeSearchButton( 'go', array( 'id' => 'searchButton', 'class' => 'searchButton' ) );
			?>
		</div>
	</form>
</div>
<?php

				break;
			}
		}
	}
}
