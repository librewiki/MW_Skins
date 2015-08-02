<?php

/**
 * Paper - Vector 기반으로 작성된 모니위키 짭
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
 * QuickTemplate class for Vector skin
 * @ingroup Skins
 */
class PaperTemplate extends BaseTemplate {

	/* Functions */

	/**
	 * Outputs the entire contents of the (X)HTML page
	 */
	public function execute() {
		global $wgPaperUseIconWatch;

		$moniIconList = array('편집' => '/moniwiki/imgs/moni2/edit.png',
			'역사' => '/moniwiki/imgs/moni2/info.png',
			'보기' => '/moniwiki/imgs/moni2/show.png',
			'역링크' => '/moniwiki/imgs/moni2/backlinks.png',
			'임의 문서로' => '/moniwiki/imgs/moni2/random.png',
			'검색' => '/moniwiki/imgs/moni2/search.png',
			'차이' => '/moniwiki/imgs/moni2/diff.png',
			'도움말' => '/moniwiki/imgs/moni2/help.png',
			'환경설정' => '/moniwiki/imgs/moni2/pref.png',
		'RSS' => '/moniwiki/imgs/moni2/rss.png');

		// Build additional attributes for navigation urls
		$nav = $this->data['content_navigation'];

		$_TITLE = $this->getSkin()->getRelevantTitle();
		$_URITITLE = rawurlencode($_TITLE);
		
/*
		if ( $wgPaperUseIconWatch ) {
			$mode = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() ) ? 'unwatch' : 'watch';
			if ( isset( $nav['actions'][$mode] ) ) {
				$nav['views'][$mode] = $nav['actions'][$mode];
				$nav['views'][$mode]['class'] = rtrim( 'icon ' . $nav['views'][$mode]['class'], ' ' );
				$nav['views'][$mode]['primary'] = true;
				unset( $nav['actions'][$mode] );
			}
		}*/

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
#		if($_SERVER['REMOTE_ADDR'] == '182.216.191.202')
#			print_r($nav);
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
		// Output HTML Page
		$this->html( 'headelement' );
?>
		<div><a id="top" name="top" accesskey="t"></a></div>
		<script type="text/javascript">
		function toggle_menu() {
			var menu = document.getElementById('wikiMenu');
			if (!menu) return;

			if (menu.style.height == 0)
				menu.style.height = 0;
			if (parseInt(menu.style.height) == 0) {
				menu.style.height = 'auto';
			} else {
				menu.style.height = '0';
			}
			console.log(menu.style.height);
		}
		</script>

	
		<div id="p-personal" role="navigation" class="<?php if ( count( $this->data['personal_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-personal-label" >
			<div id="p-personal-align-center">

				<!-- <h3 id="p-personal-label">Libre Wiki <?php $this->msg( 'personaltools' ) ?></h3> -->
				<ul<?php $this->html( 'userlangattributes' ) ?>>
			<?php
								$personalTools = $this->getPersonalTools();
								foreach ( $personalTools as $key => $item ) {
									echo $this->makeListItem( $key, $item );
								}
			?>
				</ul>
			</div>
		</div>

		<div id="mw-js-message" style="display:none;"<?php $this->html( 'userlangattributes' ) ?>></div>
		<?php if ( $this->data['sitenotice'] ) { ?>
		<?php } ?>
		<div id="mainBody">
			<!--
			<div id="wikiIcon">
				<a href="/index.php?title=<?php echo $_URITITLE; ?>&action=edit" accesskey="e" title="편집"><span><img src="../skins/Paper/moniwiki/imgs/moni2/edit.png" alt="E" class="wikiIcon"></span></a>
				<a href="/index.php?title=<?php echo $_URITITLE; ?>&diff=" accesskey="c" title="차이 보기"><span><img src="../skins/Paper/moniwiki/imgs/moni2/diff.png" alt="D" class="wikiIcon"></span></a>
				<a href="/wiki/<?php echo $_URITITLE; ?>" title="읽기"><span><img src="../skins/Paper/moniwiki/imgs/moni2/show.png" alt="R" class="wikiIcon"></span></a>
				<a href="/index.php?title=Special:WhatLinksHere&target=<?php echo $_URITITLE; ?>" rel="nofollow" title="가리키는 문서"><span><img src="../skins/Paper/moniwiki/imgs/moni2/backlinks.png" alt="," class="wikiIcon"></span></a>
				<a href="/wiki/Special:Random" rel="nofollow" title="임의 문서로"><span><img src="../skins/Paper/moniwiki/imgs/moni2/random.png" alt="A" class="wikiIcon"></span></a>
				<a href="/wiki/Special:Search" title="검색"><span><img src="../skins/Paper/moniwiki/imgs/moni2/search.png" alt="S" class="wikiIcon"></span></a>
				<a href="/index.php?title=<?php echo $_URITITLE; ?>&action=history" rel="nofollow" title="역사 보기"><span><img src="../skins/Paper/moniwiki/imgs/moni2/info.png" alt="I" class="wikiIcon"></span></a>
				<a href="/wiki/%EB%8F%84%EC%9B%80%EB%A7%90:%EC%9C%84%ED%82%A4_%EB%AC%B8%EB%B2%95" title="도움말"><span><img src="../skins/Paper/moniwiki/imgs/moni2/help.png" alt="H" class="wikiIcon"></span></a>
				<a href="/wiki/Special:Preferences" title="환경설정"><span><img src="../skins/Paper/moniwiki/imgs/moni2/pref.png" alt="C" class="wikiIcon"></span></a>
				<a href="/index.php?title=Special:RecentChanges&feed=atom" rel="nofollow" title="Atom"><span><img src="../skins/Paper/moniwiki/imgs/moni2/rss.png" alt="RSS" class="wikiIcon"></span></a>
			</div>
			-->
			<div class="navbar-fixed-top navbar">
				<div class="navbar-inner">
					<div class="container">
						<button type="button" class="btn btn-navbar" onclick="toggle_menu()">
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>
						<span class="brand hidden-desktop"><a href="/wiki/FrontPage"><span>Libre Wiki</span></a></span>
						<div id="wikiMenu" class="collapse">
							<div id="goForm">
								<form id="go" action="/index.php" method="get">
								<div>
									<input type="text" name="search" size="20" accesskey="s" class="goto">
									<input type="hidden" value="Special:Search" name="title"/>
									<input type="submit" name="go" class="submitBtn" value="Go">
								</div>
								</form>
							</div>
							<ul><?php foreach($this->data['sidebar']['navigation'] as $val) { 
					echo '<li id="'.$val['id'].'"><a href="'.$val['href'].'" id rel="nofollow">'.$val['text'].'</a></li>';
				} ?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div id="wikiIcon">
				<a href="/index.php?title=<?php echo $_URITITLE; ?>&action=edit" accesskey="e" title="편집"><span><img src="//<?=$_SERVER["HTTP_HOST"];?>/skins/Paper/moniwiki/imgs/moni2/edit.png" alt="E" class="wikiIcon"></span></a>
				<a href="/index.php?title=<?php echo $_URITITLE; ?>&diff=" accesskey="c" title="차이 보기"><span><img src="//<?=$_SERVER["HTTP_HOST"];?>/skins/Paper/moniwiki/imgs/moni2/diff.png" alt="D" class="wikiIcon"></span></a>
				<a href="/wiki/<?php echo $_URITITLE; ?>" title="읽기"><span><img src="//<?=$_SERVER["HTTP_HOST"];?>/skins/Paper/moniwiki/imgs/moni2/show.png" alt="R" class="wikiIcon"></span></a>
				<a href="/index.php?title=Special:WhatLinksHere&target=<?php echo $_URITITLE; ?>" rel="nofollow" title="가리키는 문서"><span><img src="//<?=$_SERVER["HTTP_HOST"];?>/skins/Paper/moniwiki/imgs/moni2/backlinks.png" alt="," class="wikiIcon"></span></a>
				<a href="/wiki/Special:Random" rel="nofollow" title="임의 문서로"><span><img src="//<?=$_SERVER["HTTP_HOST"];?>/skins/Paper/moniwiki/imgs/moni2/random.png" alt="A" class="wikiIcon"></span></a>
				<a href="/wiki/Special:Search" title="검색"><span><img src="//<?=$_SERVER["HTTP_HOST"];?>/skins/Paper/moniwiki/imgs/moni2/search.png" alt="S" class="wikiIcon"></span></a>
				<a href="/index.php?title=<?php echo $_URITITLE; ?>&action=history" rel="nofollow" title="역사 보기"><span><img src="//<?=$_SERVER["HTTP_HOST"];?>/skins/Paper/moniwiki/imgs/moni2/info.png" alt="I" class="wikiIcon"></span></a>
				<a href="/wiki/%EB%8F%84%EC%9B%80%EB%A7%90:%EC%9C%84%ED%82%A4_%EB%AC%B8%EB%B2%95" title="도움말"><span><img src="//<?=$_SERVER["HTTP_HOST"];?>/skins/Paper/moniwiki/imgs/moni2/help.png" alt="H" class="wikiIcon"></span></a>
				<a href="/wiki/Special:Preferences" title="환경설정"><span><img src="//<?=$_SERVER["HTTP_HOST"];?>/skins/Paper/moniwiki/imgs/moni2/pref.png" alt="C" class="wikiIcon"></span></a>
				<a href="/index.php?title=Special:RecentChanges&feed=atom" rel="nofollow" title="Atom"><span><img src="//<?=$_SERVER["HTTP_HOST"];?>/skins/Paper/moniwiki/imgs/moni2/rss.png" alt="RSS" class="wikiIcon"></span></a>
			</div>
			<div id="container">
				<div id="mycontent">
					<div id="siteNotice"><?php $this->html( 'sitenotice' ) ?></div>
					<div class="wikiTitle" id="wikiTitle">
						<span class="wikiTitle"><a href="/index.php?title=Special:WhatLinksHere&target=<?php echo $_URITITLE; ?>" rel="nofollow"><span><?php $this->html( 'title' ) ?></span></a></span>
					</div>
					<?php $this->html( 'prebodyhtml' ) ?>
					<div id="wikiBody">
						<div id="wikiContent">
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
				</div>
			</div>
		</div>
		<div id="wikiFooter">

		<?php $_footerLinks = $this->getFooterLinks(); ?>
			<div id="wikiAction">
				<ul>
				<?php foreach ( $this->data['action_urls'] as $link ) { ?>
					<li><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><span><?php echo htmlspecialchars( $link['text'] ) ?></span></a></li>
				<?php } ?>
			</div>
			<div style="align:center" id="wikiBanner">
				<p><?php $this->html( 'copyright' ) ?></p>
				<p>
				<?php foreach ( $_footerLinks['places'] as $link ) { ?>
					<span id="footer-places-<?php echo $link ?>"><?php $this->html( $link ) ?></span>　
				<?php } ?>
				</p>
			</div>
		</div>
		<?php $this->printTrail(); ?>

	</body>
</html>
<?php
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
<div id="p-namespaces" role="navigation" class="paperTabs<?php if ( count( $this->data['namespace_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-namespaces-label">
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
<div id="p-variants" role="navigation" class="paperMenu<?php if ( count( $this->data['variant_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-variants-label">
	<h3 id="mw-paper-current-variant">
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
		<?php foreach ( $this->data['view_urls'] as $link ) { ?>
			<a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><span><?php
				// $link['text'] can be undefined - bug 27764
				if ( array_key_exists( 'text', $link ) ) {
					echo array_key_exists( 'img', $link ) ? '<img src="' . $link['img'] . '" alt="' . $link['text'] . '" />' : htmlspecialchars( $link['text'] );
				}
				?></span></a>
		<?php } ?>

<?php
				break;
				case 'ACTIONS':
?>
			<?php foreach ( $this->data['action_urls'] as $link ) { ?>
				<a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><span><?php echo htmlspecialchars( $link['text'] ) ?></span></a>
			<?php } ?>
<?php
				break;
				case 'PERSONAL':
?>
<div id="p-personal" role="navigation" class="<?php if ( count( $this->data['personal_urls'] ) == 0 ) { echo ' emptyPortlet'; } ?>" aria-labelledby="p-personal-label">
	<h3 id="p-personal-label"><?php $this->msg( 'personaltools' ) ?></h3>
	<ul<?php $this->html( 'userlangattributes' ) ?>>
<?php
					$personalTools = $this->getPersonalTools();
					foreach ( $personalTools as $key => $item ) {
						echo $this->makeListItem( $key, $item );
					}
?>
	</ul>
</div>
<?php
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
