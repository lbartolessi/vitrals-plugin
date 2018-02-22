<?php
/**
 * Vitrals Plugin.
 *
 * @package     WordPress\Plugins\Vitrals
 * @author      Luis Bartolessi <luis@bartolessi.org>
 * @link        https://github.com/lbartolessi/vitrals-plugin/archive/master.zip
 * @version     1.0.0
 *
 * @copyright   2013-2018 Juliette Reinders Folmer
 * @license     http://creativecommons.org/licenses/GPL/2.0/ GNU General Public License, version 2 or higher
 *
 * @wordpress-plugin
 * Plugin Name: Vitrals Plugin
 * Description: Plugin that completes with shortcodes the functionalities of the theme "Vitrals".
 * Version:     1.0.0
 * Author:      Luis Bartolessi
 * Author URI:  https://bartolessi.org/
 * Copyright:   2018 Luis Bartolessi
 *
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

function slugify ( $text ) {
	// replace non letter or digits by -
	$text = preg_replace ( '~[^\pL\d]+~u', '-', $text );

	// transliterate
	$text = iconv ( 'utf-8', 'us-ascii//TRANSLIT', $text );

	// remove unwanted characters
	$text = preg_replace ( '~[^-\w]+~', '', $text );

	// trim
	$text = trim ( $text, '-' );

	// remove duplicate -
	$text = preg_replace ( '~-+~', '-', $text );

	// lowercase
	$text = strtolower ( $text );

	if ( empty ( $text ) ) {
		return 'n-a';
	}

	return $text;

}

function vitrals_shortcodes_init () {

	function vitrals_youtube_shortcode ( $atts, $content = null ) {
		extract ( shortcode_atts ( array (
			'caption'	 => '',
			'id'		 => '' ), $atts ) );

		if ( empty ( $id ) ) {
			$id = preg_replace ( '/\=?(https?\:\/\/)?(www\.youtube\.com|youtu\.?be)\/(watch\?v\=|embed\/)?([^"]+)/',
						'$4', $atts[ 0 ] );
		}
		return sprintf ( ' <figure><a class="btn lazy-embed embed-responsive embed-responsive-16by9 mb-4" data-service="youtube" data-id="%1$s">'
		. '     <img alt="%2$s" class="embed-responsive-item" src="https://img.youtube.com/vi/%1$s/default.jpg">'
		. ' </a>'
		. ' <div id="%1$s"  class="lazy-embed embed-responsive embed-responsive-16by9 mb-4">'
		. '     <iframe class="embed-responsive-item" allowfullscreen ></iframe>'
		. ' </div>'
		. '     <figcaption>%2$s</figcaption>'
		. '</figure>', $id, $caption );

	}

	add_shortcode ( 'youtube', 'vitrals_youtube_shortcode' );

	function vitrals_archiveorg_shortcode ( $atts, $content = null ) {
		extract ( shortcode_atts ( array (
			'caption'	 => '',
			'id'		 => '' ), $atts ) );

		return sprintf ( ' <figure>'
		. ' <div id="%1$s" class="embed-responsive embed-responsive-16by9 mb-4">'
		. '     <iframe src="https://archive.org/embed/%1$s" width="640" height="480" frameborder="0" webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen></iframe>'
		. ' </div>'
		. '     <figcaption>%2$s</figcaption>'
		. '</figure>', $id, $caption );

	}

	add_shortcode ( 'archiveorg', 'vitrals_archiveorg_shortcode' );

	function vitrals_ted_shortcode ( $atts, $content = null ) {
		extract ( shortcode_atts ( array (
			'caption'	 => '',
			'id'		 => '' ), $atts ) );

		return sprintf ( ' <figure>'
		. ' <div id="%1$s"  class="embed-responsive embed-responsive-16by9 mb-4">'
		. '     <iframe class="embed-responsive-item"  src="https://embed.ted.com/talks/%1$s" allowfullscreen ></iframe>'
		. '</div>'
		. '     <figcaption>%2$s</figcaption>'
		. '</figure>', $id, $caption );

	}

	add_shortcode ( 'ted', 'vitrals_ted_shortcode' );

	function vitrals_dailymotion_shortcode ( $atts, $content = null ) {
		extract ( shortcode_atts ( array (
			'caption'	 => '',
			'id'		 => '' ), $atts ) );

		return sprintf ( ' <figure>'
		. '<a class="btn lazy-embed embed-responsive embed-responsive-16by9 mb-4" data-service="dailymotion" data-id="%1$s">'
		. '     <img alt="%2$s"  class="embed-responsive-item" src="https://www.dailymotion.com/thumbnail/video/%1$s">'
		. ' </a>'
		. ' <div id="%1$s"  class="lazy-embed embed-responsive embed-responsive-16by9 mb-4">'
		. '     <iframe class="embed-responsive-item" allowfullscreen ></iframe>'
		. '</div>'
		. '     <figcaption>%2$s</figcaption>'
		. '</figure>', $id, $caption );

	}

	add_shortcode ( 'dailymotion', 'vitrals_dailymotion_shortcode' );

	function vitrals_vimeo_shortcode ( $atts, $content = null ) {
		extract ( shortcode_atts ( array (
			'caption'	 => '',
			'id'		 => '' ), $atts ) );

		if ( empty ( $id ) ) {
			$id = $atts[ 0 ];
		}

		$vimeo_img = unserialize ( file_get_contents ( "http://vimeo.com/api/v2/video/"
		. $id . ".php" ) )[ 0 ][ 'thumbnail_medium' ];
		return sprintf ( ' <figure>'
		. ' <a class="btn lazy-embed embed-responsive embed-responsive-16by9 mb-4" data-service="vimeo" data-id="%1$s">'
		. '     <img alt="%2$s"  class="embed-responsive-item" src="%3$s">'
		. ' </a>'
		. ' <div id="%1$s"  class="lazy-embed embed-responsive embed-responsive-16by9 mb-4">'
		. '     <iframe class="embed-responsive-item" allowfullscreen ></iframe>'
		. '</div>'
		. '     <figcaption>%2$s</figcaption>'
		. '</figure>', $id, $caption, ($vimeo_img ? $vimeo_img : '' ) );

	}

	add_shortcode ( 'vimeo', 'vitrals_vimeo_shortcode' );

	function vitrals_left_content ( $atts, $content = null ) {
		if ( ! $content )
			return;

		$content = apply_filters ( 'the_content', $content );
		$content = str_replace ( ']]>', ']]&gt;', $content );

		$left_content = get_query_var ( 'left_content' ) . $content;
		set_query_var ( 'left_content', $left_content );
		return '';

	}

	add_shortcode ( 'left', 'vitrals_left_content' );

	function vitrals_right_content ( $atts, $content = null ) {
		if ( ! $content )
			return;

		$content = apply_filters ( 'the_content', $content );
		$content = str_replace ( ']]>', ']]&gt;', $content );

		$right_content = get_query_var ( 'right_content' ) . $content;
		set_query_var ( 'right_content', $right_content );
		return '';

	}

	add_shortcode ( 'right', 'vitrals_right_content' );

	function vitrals_footnote ( $atts, $content = null ) {
		extract ( shortcode_atts ( array (
			'id' => '' ), $atts ) );

		return sprintf ( '<a id="note-'
		. slugify ( $id )
		. '" class="modal-note" href="javascript:return false;">'
		. '<sup>&nbsp;[' . $id . ']&nbsp;</sup></a>' );

	}

	add_shortcode ( 'footnote', 'vitrals_footnote' );

	function vitrals_foot_text ( $atts, $content = null ) {
		extract ( shortcode_atts ( array (
			'id' => '' ), $atts ) );

		$content	 = apply_filters ( 'the_content', $content );
		$content	 = str_replace ( ']]>', ']]&gt;', $content );
		$foot_notes	 = get_query_var ( 'foot_notes' );

		if ( ! $foot_notes )
			$foot_notes = array ();

		$foot_notes[ $id ] = $content;
		set_query_var ( 'foot_notes', $foot_notes );
		return '';

	}

	add_shortcode ( 'textnote', 'vitrals_foot_text' );

}

add_action ( 'init', 'vitrals_shortcodes_init' );

function vitrals_get_inline_image_src ( $url ) {

	$path	 = parse_url ( $url, PHP_URL_PATH );
	$type	 = pathinfo ( $path, PATHINFO_EXTENSION );
	$data	 = file_get_contents ( $url );
	return 'data:image/' . $type . ';base64,' . base64_encode ( $data );

}

function vitralsResponsiveEmbeds ( $content ) {
	$patterns	 = array ();
	$iframes	 = array ();

	$patterns[]	 = '/<a.*href="(https?\:\/\/)?(www\.|player\.)?vimeo\.com\/(video\/)?([^"]+)"[^>]*>(.*)<\/a>/';
	$iframes[]	 = '[vimeo id=$4 caption=\'$5\']';
	$patterns[]	 = '/<a.*href="(https?\:\/\/)?(www\.youtube\.com|youtu\.?be)\/(watch\?v\=|embed\/)?([^"]+)"[^>]*>(.*)<\/a>/';
	$iframes[]	 = '[youtube id=$4 caption=\'$5\']';
	$patterns[]	 = '/<a.*href="(https?\:\/\/)?www\.dailymotion\.com\/(embed\/)?video\/([^"]+)"[^>]*>(.*)<\/a>/';
	$iframes[]	 = '[dailymotion id=$3 caption=\'$4\']';

	$patterns[]	 = '/<a.*href="(https?\:\/\/)?www\.ted\.com\/talks\/([^"]+)"[^>]*>(.*)<\/a>/';
	$iframes[]	 = '[ted id=$2 caption=\'$3\']';

	return preg_replace ( $patterns, $iframes, $content );

}

add_filter ( 'the_content', 'vitralsResponsiveEmbeds', 6 );
add_filter ( 'comment_text', 'vitralsResponsiveEmbeds', 6 );
