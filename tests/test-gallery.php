<?php
/**
 * Class SampleTest
 *
 * @package Masonry_Gallery
 */

/**
 * Sample test case.
 */
class Gallery_Test extends WP_UnitTestCase
{
	function test_gallery()
	{
		$ids = array();
		for ( $i = 0; $i < 10; $i++) {
			$ids[] = $this->factory()->attachment->create_object( 'image-'. $i .'.jpg', false, array(
				'post_mime_type' => 'image/jpeg',
				'post_type' => 'attachment'
			) );
		}

		$html = do_shortcode( '[gallery ids="' . join( ',', $ids ) . '"]' );

		$this->assertMaybeValidHTML( $html );

		$dom = $this->dom( $html );
		$this->assertSame( 10, count( $dom->filter( 'figure.underscore-gallery-item' ) ) );
		$this->assertSame( 1, count( $dom->filter( 'div.underscore-gallery.column-3' ) ) );

		$node = $dom->filter( 'figure.underscore-gallery-item' )->eq( 0 );
		$this->assertSame( 1, count( $node->filter( 'a' ) ) );
		$this->assertSame( 1, count( $node->filter( 'a img.attachment-thumbnail.size-thumbnail' ) ) );
	}

	private function dom( $html )
	{
		$dom = new Symfony\Component\DomCrawler\Crawler();
		$dom->addHTMLContent( $html, "UTF-8" );
		return $dom;
	}

	private function assertMaybeValidHTML( $html )
	{
		$html = str_replace( 'section', 'div', $html );
		$html = str_replace( 'aside', 'div', $html );
		$html = str_replace( 'figure', 'div', $html );
		$dom = new DOMDocument();
		$dom->loadHTML( '<html><body>' . $html . '</body></html>' );
	}
}
