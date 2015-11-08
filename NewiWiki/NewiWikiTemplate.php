<?php
/**
 * NewiWiki - Modern version of MonoBook with fresh look and many usability
 * improvements.
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
 * @file
 * @ingroup Skins
 */

/**
 * QuickTemplate class for NewiWiki skin
 * @ingroup Skins
 */
class NewiWikiTemplate extends BaseTemplate {
	/* Functions */

	/**
	 * Outputs the entire contents of the (X)HTML page
	 */
	public function execute() {
		global $wgVectorUseIconWatch, $wgUser;

		// Build additional attributes for navigation urls
		$nav = $this->data['content_navigation'];

		if ( $this->config->get( 'NewiWikiUseIconWatch' ) ) {
			$mode = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() )
				? 'unwatch'
				: 'watch';

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
						$notificationCount = MWEchoNotifUser::newFromUser( $this->getSkin()->getUser() )->getNotificationCount();
					?>
					<button type="button" class="btn <?php if ($notificationCount) {
						echo "btn-danger";
					} else {
						echo "btn-success";
					}
					?> loginbtn navbar-right" data-toggle="collapse" href="#personalbar" aria-expanded="false" aria-controls="personalbar">
					<?=$wgUser->getName();?>
					<?php
					?>
					</button>
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
							//echo $title->getArticleID();
							//echo $this->getSkin()->getOldID();
							//echo $this->getSkin()->getActionName();
					?>
						<li><?php echo Linker::linkKnown( $title, '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 편집', array(), array( 'action' => 'edit', 'oldid' => $this->getSkin()->getRevisionId() ) ); ?></li>
						<li><?php echo Linker::linkKnown( $title, '<span class="glyphicon glyphicon-menu-right" aria-hidden="true"></span> 새문단', array(), array( 'action' => 'edit', 'section' => 'new' ) ); ?></li>
						<li><?php echo Linker::linkKnown( $title, '<span class="glyphicon glyphicon-time" aria-hidden="true"></span> 기록', array(), array( 'action' => 'history' ) ); ?></li>
					<?php
            if ( $companionTitle ) {
                if ($title->getNamespace() == NS_TALK || $title->getNamespace() == NS_PROJECT_TALK || $title->getNamespace() == NS_FILE_TALK || $title->getNamespace() == NS_TEMPLATE_TALK) {
                    ?>
                    <li><?php echo Linker::linkKnown( $companionTitle, '<span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> 본문' ); ?></li>
                    <?php
                } else {
                ?>
                    <li><?php echo Linker::linkKnown( $companionTitle, '<span class="glyphicon glyphicon-fire" aria-hidden="true"></span> 토론'); ?></li>
                    <?php
                }
            } ?>
			<li><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'WhatLinksHere', $title ), '<span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span> 역링크' ); ?></li>
            <?php
            $mode = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() ) ? 'unwatch' : 'watch';
            if ($mode != 'watch') {
                $watchname = '주시해제';
		$emptystar = '';
            } else {
                $watchname = '주시';
                $emptystar = '-empty';
            }
            if ( $title->getNamespace() != NS_SPECIAL ) {
            ?>
            <li><?php echo Linker::linkKnown( $title, '<span class="glyphicon glyphicon-star' . $emptystar . '" aria-hidden="true"></span> ' . $watchname, array(), array( 'action' => $mode ) ); ?></li>
            <?php
            }
            ?>
            <li><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'Movepage', $title ), '<span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span> 옮기기' ); ?></li>
            <?php if ( $title->quickUserCan( 'protect', $user ) ) { ?>
            <li><?php echo Linker::linkKnown( $title, '<span class="glyphicon glyphicon-lock" aria-hidden="true"></span> 보호', array(), array( 'action' => 'protect' ) ); ?></li>
            <?php } ?>
            <?php if ( $title->quickUserCan( 'delete', $user ) ) {
            ?>
            <li><?php echo Linker::linkKnown( $title, '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 삭제', array(), array( 'action' => 'delete' ) ); ?></li>
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

									<!-- Footer ad -->
								<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
								<ins class="adsbygoogle"
									 style="display:block; min-width:320px; width:100%; height:90px;margin-top:15px;"
									 data-ad-client="ca-pub-2664061841343191"
									 data-ad-slot="6502738263"
									 data-ad-format="auto"></ins>
								<script>
									(adsbygoogle = window.adsbygoogle || []).push({});
								</script>
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
<?php                   foreach ( $footericons as $blockName => $footerIcons ) { ?>
                                        <li id="footer-<?php echo htmlspecialchars( $blockName ); ?>ico">
<?php                           foreach ( $footerIcons as $icon ) { ?>
                                                <?php echo $this->getSkin()->makeFooterIcon( $icon ); ?>

<?php                           } ?>
                                        </li>
<?php                   } ?>
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

																								<!-- sidebar ad -->
																								<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
																								<!-- 사이드바 -->
																								<ins class="adsbygoogle"
																								     style="display:block;max-width:230px;margin-top:10px;margin-left:10px;height:100%;max-height:600px;"
																								     data-ad-client="ca-pub-2664061841343191"
																								     data-ad-slot="2751059465"
																								     data-ad-format="auto"></ins>
																								<script>
																								(adsbygoogle = window.adsbygoogle || []).push({});
																								</script>
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
        
                <script>
                    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                    ga('create', 'UA-69833185-1', 'auto');
                    ga('send', 'pageview');

                </script>
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
	 * @param array $portals
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

			// Numeric strings gets an integer when set as key, cast back - T73639
			$name = (string)$name;

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
	 * @param string $name
	 * @param array $content
	 * @param null|string $msg
	 * @param null|string|array $hook
	 */
	protected function renderPortal( $name, $content, $msg = null, $hook = null ) {
		if ( $msg === null ) {
			$msg = $name;
		}
		$msgObj = wfMessage( $msg );
		?>
		<div class="portal" role="navigation" id='<?php
		echo Sanitizer::escapeId( "p-$name" )
		?>'<?php
		echo Linker::tooltip( 'p-' . $name )
		?> aria-labelledby='<?php echo Sanitizer::escapeId( "p-$name-label" ) ?>'>
			<h3<?php
			$this->html( 'userlangattributes' )
			?> id='<?php
			echo Sanitizer::escapeId( "p-$name-label" )
			?>'><?php
				echo htmlspecialchars( $msgObj->exists() ? $msgObj->text() : $msg );
				?></h3>

			<div class="body">
				<?php
				if ( is_array( $content ) ) {
					?>
					<ul>
						<?php
						foreach ( $content as $key => $val ) {
							?>
							<?php echo $this->makeListItem( $key, $val ); ?>

						<?php
						}
						if ( $hook !== null ) {
							wfRunHooks( $hook, array( &$this, true ) );
						}
						?>
					</ul>
				<?php
				} else {
					?>
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
	 * @param array $elements
	 */
	protected function renderNavigation( $elements ) {
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
					<div id="p-namespaces" role="navigation" class="newiwikiTabs<?php
					if ( count( $this->data['namespace_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-namespaces-label">
						<h3 id="p-namespaces-label"><?php $this->msg( 'namespaces' ) ?></h3>
						<ul<?php $this->html( 'userlangattributes' ) ?>>
							<?php
							foreach ( $this->data['namespace_urls'] as $link ) {
								?>
								<li <?php
								echo $link['attributes']
								?>><span><a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" <?php
										echo $link['key']
										?>><?php
											echo htmlspecialchars( $link['text'] )
											?></a></span></li>
							<?php
							}
							?>
						</ul>
					</div>
					<?php
					break;
				case 'VARIANTS':
					?>
					<div id="p-variants" role="navigation" class="newiwikiMenu<?php
					if ( count( $this->data['variant_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-variants-label">
						<?php
						// Replace the label with the name of currently chosen variant, if any
						$variantLabel = $this->getMsg( 'variants' )->text();
						foreach ( $this->data['variant_urls'] as $link ) {
							if ( stripos( $link['attributes'], 'selected' ) !== false ) {
								$variantLabel = $link['text'];
								break;
							}
						}
						?>
						<h3 id="p-variants-label"><span><?php echo htmlspecialchars( $variantLabel ) ?></span><a href="#"></a></h3>

						<div class="menu">
							<ul>
								<?php
								foreach ( $this->data['variant_urls'] as $link ) {
									?>
									<li<?php
									echo $link['attributes']
									?>><a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" lang="<?php
										echo htmlspecialchars( $link['lang'] )
										?>" hreflang="<?php
										echo htmlspecialchars( $link['hreflang'] )
										?>" <?php
										echo $link['key']
										?>><?php
											echo htmlspecialchars( $link['text'] )
											?></a></li>
								<?php
								}
								?>
							</ul>
						</div>
					</div>
					<?php
					break;
				case 'VIEWS':
					?>
					<div id="p-views" role="navigation" class="newiwikiTabs<?php
					if ( count( $this->data['view_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-views-label">
						<h3 id="p-views-label"><?php $this->msg( 'views' ) ?></h3>
						<ul<?php
						$this->html( 'userlangattributes' )
						?>>
							<?php
							foreach ( $this->data['view_urls'] as $link ) {
								?>
								<li<?php
								echo $link['attributes']
								?>><span><a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" <?php
										echo $link['key']
										?>><?php
											// $link['text'] can be undefined - bug 27764
											if ( array_key_exists( 'text', $link ) ) {
												echo array_key_exists( 'img', $link )
													? '<img src="' . $link['img'] . '" alt="' . $link['text'] . '" />'
													: htmlspecialchars( $link['text'] );
											}
											?></a></span></li>
							<?php
							}
							?>
						</ul>
					</div>
					<?php
					break;
				case 'ACTIONS':
					?>
					<div id="p-cactions" role="navigation" class="newiwikiMenu<?php
					if ( count( $this->data['action_urls'] ) == 0 ) {
						echo ' emptyPortlet';
					}
					?>" aria-labelledby="p-cactions-label">
						<h3 id="p-cactions-label"><span><?php
							$this->msg( 'newiwiki-more-actions' )
						?></span><a href="#"></a></h3>

						<div class="menu">
							<ul<?php $this->html( 'userlangattributes' ) ?>>
								<?php
								foreach ( $this->data['action_urls'] as $link ) {
									?>
									<li<?php
									echo $link['attributes']
									?>>
										<a href="<?php
										echo htmlspecialchars( $link['href'] )
										?>" <?php
										echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] )
											?></a>
									</li>
								<?php
								}
								?>
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
						<h3<?php $this->html( 'userlangattributes' ) ?>>
							<label for="searchInput"><?php $this->msg( 'search' ) ?></label>
						</h3>

						<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
							<?php
							if ( $this->config->get( 'NewiWikiUseSimpleSearch' ) ) {
							?>
							<div id="simpleSearch">
								<?php
							} else {
							?>
								<div>
									<?php
							}
							?>
							<?php
							echo $this->makeSearchInput( array( 'id' => 'searchInput' ) );
							echo Html::hidden( 'title', $this->get( 'searchtitle' ) );
							// We construct two buttons (for 'go' and 'fulltext' search modes),
							// but only one will be visible and actionable at a time (they are
							// overlaid on top of each other in CSS).
							// * Browsers will use the 'fulltext' one by default (as it's the
							//   first in tree-order), which is desirable when they are unable
							//   to show search suggestions (either due to being broken or
							//   having JavaScript turned off).
							// * The mediawiki.searchSuggest module, after doing tests for the
							//   broken browsers, removes the 'fulltext' button and handles
							//   'fulltext' search itself; this will reveal the 'go' button and
							//   cause it to be used.
							echo $this->makeSearchButton(
								'fulltext',
								array( 'id' => 'mw-searchButton', 'class' => 'searchButton mw-fallbackSearchButton' )
							);
							echo $this->makeSearchButton(
								'go',
								array( 'id' => 'searchButton', 'class' => 'searchButton' )
							);
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
