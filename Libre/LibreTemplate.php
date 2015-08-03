<?php
/**
 * Libre - Modern version of MonoBook with fresh look and many usability
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
 * QuickTemplate class for Libre skin
 * @ingroup Skins
 */
class LibreTemplate extends BaseTemplate {
	/* Functions */

	/**
	 * Outputs the entire contents of the (X)HTML page
	 */
	public function execute() {
		// Build additional attributes for navigation urls
		global $wgUser;
		$nav = $this->data['content_navigation'];

		if ( $this->config->get( 'LibreUseIconWatch' ) ) {
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
		<div class="container">

			<!-- Static navbar -->
			<nav class="navbar navbar-default navbar-fixed-top libre_navbar">
				<div class="container">
					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
							<span class="sr-only">Toggle navigation</span>
							<span class="glyphicon glyphicon-th-large" aria-hidden="true"></span>
						</button>
						<div class="dropdown">
							<?php
							if ( $wgUser->isLoggedIn() ) {
								$loginfuc = "data-toggle=\"dropdown\"";
								$loginbtn = "user";
							} else {
								$loginbtn = "lock";
								$loginfuc = "onclick=\"location.href='" . $userLink['href'] . "'\"";
							}
 							?>
							<button type="button" class="navbar-toggle collapsed dropdown-toggle" <?=$loginfuc;?> aria-expanded="false">
 								<span class="sr-only">Toggle navigation</span>
								<span class="glyphicon glyphicon-<?=$loginbtn;?>" aria-hidden="true"></span>
				                        	</button>
							<?php
							if ( $wgUser->isLoggedIn() ) {
							?>
							<ul class="dropdown-menu libre_personal_dropdown" role="menu">
								<?php $this->renderNavigation( 'PERSONAL' ); ?>
							</ul>
							<?php
							}
							?>
						</div>
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav_search" aria-expanded="false" aria-controls="nav_search">
							<span class="sr-only">Toggle navigation</span>
							<span class="glyphicon glyphicon-search" aria-hidden="true"></span>
			                        </button>
						<a class="navbar-brand" href="/wiki/FrontPage"><img alt="<?=$this->msg( 'tagline' );?>" src="//librewiki.net/skins/Libre/logo.png" style="width: auto; height: 36px" /></a>
					</div>
					<div id="navbar" class="navbar-collapse collapse">
						<ul class="nav navbar-nav libre_navbar_menu">
							<li><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'Recentchanges', null ), '최근바뀜', array( 'title' => '최근 변경 문서를 불러옵니다. [alt+shift+r]', 'accesskey' => 'r') ); ?></li>
							<li><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'Randompage', null ), '임의문서', array( 'title' => '임의 문서를 불러옵니다. [alt+shift+x]', 'accesskey' => 'x' ) ); ?></li>
							<li><a href="https://bbs.librewiki.net/wiki">위키방</a></li>
							<li><a href="https://bbs.librewiki.net/anon">익명게시판</a></li>
							<li class="dropdown">
								<?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'Specialpages', null ), '도구', array( 'data-toggle' => 'dropdown', ' role' => 'button', 'aria-expanded' => 'false', 'title' => '도구를 보여줍니다.') ); ?>
								<ul class="dropdown-menu" role="menu">
									<li><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'SpecialPages', null ), '특수 문서 목록', array( 'title' => '특수 문서 목록을 불러옵니다. [alt+shift+q]', 'accesskey' => 'q') ); ?></li>
									<li><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'upload', null ), '업로드', array( 'title' => '파일을 올립니다. [alt+shift+g]', 'accesskey' => 'g') ); ?></li>
								</ul>
							</li>
							<li class="dropdown">
								<?php echo Linker::linkKnown( Title::makeTitle( NS_HELP, '위키 문법' ), '도움말', array( 'data-toggle' => 'dropdown', ' role' => 'button', 'aria-expanded' => 'false', 'title' => '도움말 항목들을 보여줍니다.') ); ?>
								<ul class="dropdown-menu" role="menu">
									<li><?php echo Linker::linkKnown( Title::makeTitle( NS_HELP, '위키 문법' ), '위키 문법' ) ; ?></li>
									<li><?php echo Linker::linkKnown( Title::makeTitle( NS_HELP, 'Tex 문법' ), 'Tex 문법' ) ; ?></li>
									<li><?php echo Linker::linkKnown( Title::makeTitle( NS_HELP, '태그' ), '태그' ) ; ?></li>
								</ul>
							</li>
						</ul>
						<ul class="nav navbar-nav navbar-right nav-pills nav-login libre_navbar_menu">
							<?php
							if ( $wgUser->isLoggedIn() ) {
								$loginname = $wgUser->getName();
								$loginfuc = "data-toggle=\"dropdown\"";
								$logincheck = "";
							} else {
								$loginfuc = "onclick=\"location.href='" . $userLink['href'] . "'\"";
								$loginname = "로그인";
								$logincheck = "nav-login-btn";
							}
							?>
							<li class="dropdown"><a href="#" class="dropdown-toggle <?=$logincheck;?>" <?=$loginfuc;?> role="button" aria-expanded="false"><?=$loginname;?></a>
								<?php
								if ( $wgUser->isLoggedIn() ) {
								?>
								<ul class="dropdown-menu" role="menu">
									<?php $this->renderNavigation( 'PERSONAL' ); ?>
								</ul>
								<?php
								}
								?>
							</li>
						</ul>
						<button type="button" class="navbar-toggle navbar-search navbar-right collapsed" data-toggle="collapse" data-target="#nav_search" aria-expanded="false" aria-controls="nav_search">
                                                        <span class="sr-only">Toggle navigation</span>
                                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
					</div>
					<div id="nav_search" class="collapse libre_navbar_other">
						<form class="navbar-form navbar-right" action="<?php $this->text( 'wgScript' ) ?>">
							<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
							<?php echo $this->makeSearchInput( array( "id" => "searchInput", "class" => "form-control", "placeholder" => "검색어") ); ?>
						</form>
					</div>

				</div><!--/.container-fluid -->
    		</nav>

			<!-- Main component for a primary marketing message or call to action -->
			<div class="container-fluid libre_content">
				<div class="row">
					<div class="col-xs-9">
						<div class="libre_left_content" role="main">
							<!-- Footer ad -->
						<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
						<ins class="adsbygoogle"
							 style="display:block; min-width:320px; width:100%; height:90px"
							 data-ad-client="ca-pub-2664061841343191"
							 data-ad-slot="6502738263"
							 data-ad-format="auto"></ins>
						<script>
							(adsbygoogle = window.adsbygoogle || []).push({});
						</script>
							<div id="mw-js-message" style="display:none;"<?php $this->html( 'userlangattributes' ) ?>></div>
							<div id="mw-notification-area" class="mw-notification-area mw-notification-area-layout" style="display: none;"></div>
							<?php if ( $this->data['sitenotice'] ) { ?>
							<div id="alertmsg" class="alert alert-info siteNotice alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<?php $this->html( 'sitenotice' ) ?>
							</div>
							<?php } ?>
							<?php if ( $this->data['catlinks'] ) {
								$this->html( 'catlinks' );
							} ?>
							<?php $this->renderNavigation( 'TOOLS' ); ?>
							<h1 id="firstHeading" class="firstHeading" lang="<?php
								$this->data['pageLanguage'] = $this->getSkin()->getTitle()->getPageViewLanguage()->getHtmlCode();
								$this->text( 'pageLanguage' );
							?>"><span dir="auto"><?php $this->html( 'title' ) ?></span></h1>
							<?php if ($this->getSkin()->getTitle() == "FrontPage") {
                                                        ?>
                                                        <p><a href="#" class="darklibre_function" style="background-color: #000; color: #fff;">난 어두운 화면이 좋아요.</a></p>
                                                        <?php
                                                        }
                                                        ?>
							<?php $this->html( 'prebodyhtml' ) ?>
							<div class="libre_main_content">
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
								<?php if ( $this->data['dataAfterContent'] ) {
								$this->html( 'dataAfterContent' );
								} ?>
								<div class="visualClear"></div>
								<?php $this->html( 'debughtml' ); ?>
							</div>
						</div>
						<div id="footer" class="libre_footer" role="contentinfo"<?php $this->html( 'userlangattributes' ) ?>>
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
							<?php
								foreach ( $footericons as $blockName => $footerIcons ) { ?>
								<li id="footer-<?php echo htmlspecialchars( $blockName ); ?>ico">
								<?php
								foreach ( $footerIcons as $icon ) {
									echo $this->getSkin()->makeFooterIcon( $icon );
								} ?>
								</li>
							<?php } ?>
							</ul>
							<?php } ?>
							<div style="clear:both"></div>
						</div>
					</div>
					<div class="col-xs-3 libre_right_content">
						<div class="libre_right_fixed">
							<form class="libre_right_search" action="<?php $this->text( 'wgScript' ) ?>">
								<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
								<?php echo $this->makeSearchInput( array( "id"=>"searchInput","class" => "form-control", "placeholder" => "검색어") ); ?>
							</form>
							<div id="libre_right_toc">
							</div>
							<div id = "recent-list-div">
								<div class="libre_recent-title">
									<h2>최근 바뀜
										<span class="recent-more">
											<span class="mw-editsection-bracket">[</span>
											<a href="/wiki/특수:최근바뀜" title="최근바뀜문서">more</a>
											<span class="mw-editsection-bracket">]</span>
										</span>
									</h2>
								</div>
								<ul id = "recent-list">
									<li>불러오고 있습니다...</li>
								</ul>
							</div>
							<!-- sidebar ad -->
							<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
							<!-- Sidebar -->
							<ins class="adsbygoogle"
							     style="display:inline-block;width:230px;height:100%;max-height:600px;margin-top:10px;"
							     data-ad-client="ca-pub-2664061841343191"
							     data-ad-slot="1395737460"></ins>
							<script>
							(adsbygoogle = window.adsbygoogle || []).push({});
							</script>
						</div>
					</div>
				</div>
			</div>
		</div> <!-- /container -->
		<?php $this->printTrail(); ?>
		<div class="notice-wrap">
		</div>
		<div class="top_scroll" style="display: none">
			<a href="#" class="top_s_btn">
				<span class="glyphicon glyphicon-open"></span>
			</a>
		</div>
	</body>
</html>
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
		global $wgUser;
		$title = $this->getSkin()->getTitle();
		$mode = $this->getSkin()->getUser()->isWatched( $this->getSkin()->getRelevantTitle() ) ? 'unwatch' : 'watch';
		$userLinks = $this->getPersonalTools();
		$user = ( $wgUser->isLoggedIn() ) ? array_shift($userLinks) : array_pop($userLinks);
		$revid = $this->getSkin()->getRequest()->getText( 'oldid' );


		if ( !is_array( $elements ) ) {
			$elements = array( $elements );
			// If there's a series of elements, reverse them when in RTL mode
		} elseif ( $this->data['rtl'] ) {
			$elements = array_reverse( $elements );
		}
		// Render elements
		foreach ( $elements as $name => $element ) {
			switch ( $element ) {
				case 'PERSONAL':
					?>
					<li id="pt-userpage"><?php echo Linker::linkKnown( Title::makeTitle( NS_USER, $wgUser->getName() ), $wgUser->getName(), array( 'title' => '내 사용자 문서. [alt+shift+u]', 'accesskey' => 'u' ) ); ?></li>
					<li><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'notifications', null ), '알림', array( 'title' => '알림 목록을 불러옵니다.', 'class' => 'mw-echo-notifications-badge' ) ); ?></li>
					<li id="pt-mytalk"><?php echo Linker::linkKnown( Title::makeTitle( NS_USER_TALK, $wgUser->getName() ), '토론', array( 'title' => '내 토론 문서. [alt+shift+m]', 'accesskey' => 'm' ) ); ?></li>
					<li id="pt-preferences"><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'preferences', null ), '환경설정', array( 'title' => '환경설정을 불러옵니다.' ) ); ?></li>
					<li id="pt-watchlist"><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'watchlist', null ), '주시문서', array( 'title' => '주시문서를 불러옵니다. [alt+shift+l]', 'accesskey' => 'l' ) ); ?></li>
					<li id="pt-mycontris"><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'Contributions', $wgUser->getName() ), '기여', array( 'title' => '내 기여 목록을 불러옵니다. [alt+shift+y]', 'accesskey' => 'y' ) ); ?></li>
					<li id="pt-logout"><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'logout', null ), '로그아웃', array( 'title' => '로그아웃' ) ); ?></li>
					<?php
					break;
				case 'TOOLS':
					if ( $title->getNamespace() != NS_SPECIAL ) {
					$companionTitle = $title->isTalkPage() ? $title->getSubjectPage() : $title->getTalkPage();
					?>
					<ul class="libre_content_tools">
						<li class="dropdown">
							<a href="#" data-toggle="dropdown" aria-expanded="false">도구</a>
							<ul class="dropdown-menu libre_tools" role="menu">
								<li id="ca-watch">
								<?php
									if ($mode != 'watch') {
										$watchname = '주시해제';
									} else {
										$watchname = '주시';
									}
									echo Linker::linkKnown( $title, $watchname, array(), array( 'action' => $mode ) );
								?>
								</li>
								<li><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'WhatLinksHere', $title ), '역링크' ); ?></li>
								<li><?php echo Linker::linkKnown( SpecialPage::getTitleFor( 'Movepage', $title ), '옮기기', array( 'title' => '문서를 옮깁니다. [alt+shift+b]', 'accesskey' => 'b' )); ?></li>
								<?php
								if ( $title->quickUserCan( 'protect', $user ) ) { ?>
									<li><?php echo Linker::linkKnown( $title, '보호', array( 'title' => '문서를 보호합니다. [alt+shift+s]', 'accesskey' => 's' ), array( 'action' => 'protect' ) ); ?></li>
								<?php } ?>
								<?php if ( $title->quickUserCan( 'delete', $user ) ) { ?>
									<li><?php echo Linker::linkKnown( $title, '삭제', array( 'title' => '문서를 삭제합니다. [alt+shift+d]', 'accesskey' => 'd' ), array( 'action' => 'delete' ) ); ?></li>
								<?php } ?>
							</ul>
						</li>
						<li>
						<?php
						if ($companionTitle) {
							if ($title->getNamespace() == NS_TALK || $title->getNamespace() == NS_PROJECT_TALK || $title->getNamespace() == NS_FILE_TALK || $title->getNamespace() == NS_TEMPLATE_TALK) {
								$titlename = '본문';
							} else {
								$titlename = '토론';
							}
							echo Linker::linkKnown( $companionTitle, $titlename, array( 'title' => $titlename.'을 불러옵니다. [alt+shift+t]', 'accesskey' => 't') );
						}
						?>
						</li>
						<li><?php echo Linker::linkKnown( $title, '기록', array( 'title' => '문서의 편집 기록을 불러옵니다. [alt+shift+h]', 'accesskey' => 'h' ), array( 'action' => 'history' ) ); ?></li>
						<li><?php echo Linker::linkKnown( $title, '추가', array( 'title' => '새 문단을 추가합니다. [alt+shift+n]', 'accesskey' => 'n' ), array( 'action' => 'edit', 'section' => 'new' ) ); ?></li>
						<?php
						if ($revid) {
							$editaction = array( 'action' => 'edit', 'oldid' => $revid );
						} else {
							$editaction = array( 'action' => 'edit' );
						}
						?>
						<li class="thick"><?php echo Linker::linkKnown( $title, '편집', array( 'title' => '문서를 편집합니다. [alt+shift+e]', 'accesskey' => 'e' ), $editaction ); ?></li>
						<li class="hidden"><?php echo Linker::linkKnown( $title, '읽기', array( 'title' => '문서 캐쉬를 새로 지정하여 문서를 불러옵니다. [alt+shift+p]', 'accesskey' => 'p' ), array( 'action' => 'purge' ) ); ?></li>
					</ul>
					<?
					}
					break;
			}
		}
	}
}
