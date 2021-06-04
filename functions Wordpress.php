/*   
Theme Name: Rose
Theme URI: домашняя-страница-темы
Description: краткое-описание-темы
Author: ваше-имя
Author URI: ваш-URI
Template: напишите-здесь-наименование-родительской-темы--необязательное-поле
Version: номер-версии--необязательное-поле
.
Развёрнутое описание темы/Лицензия-если-нужно.
.
*/
<?php
*************************************************
    bloginfo($string) - выводит информацию о сайте

    wp_title( $sep, $echo, $seplocation ) - выводит или возвращает заголовок страницы

    <?php bloginfo('template_url'); ?>/-выводит путь к шаблону(нужно:для подключения логотипа,системных картинок);

<title><?php bloginfo('name'); wp_title(); ?></title> - выводит название сайта и активную страницу




<?php 
*************************************************
    wp_head() - запускает одноименный экшн (action), необходима для корректной работы темы и отдельных плагинов


    *************************************************
    wp_footer() - запускает одноименный экшн (action), необходима для корректной работы темы и отдельных плагинов


    *************************************************
    add_action( $tag, $function_to_add, $priority, $accepted_args ) - цепляет функцию на указанный экшн





    //ПОДКЛЮЧЕНИЕ СТИЛЕЙ И СКРИПТОВ
    *************************************************
    wp_enqueue_script( $handle, $src, $deps, $ver ) - безопасно подключает скрипт к странице

    wp_enqueue_style( $handle, $src, $deps, $ver, $media ) - ставит файл CSS стилей в очередь на загрузку


    //Подключение скриптов в fonctions.php
    function load_my_script(){

    wp_enqueue_script('magnific-popup',get_template_directory_uri() .
                      '/js/jquery.magnific-popup.min.js',array('jquery'),'',true);(array('jquery'),'',true - это штука опускает подключение скрипта из верхней части в footer)

    wp_deregister_script('jquery');-отключения скрипта по умолчанию

}
add_action('wp_enqueue_scripts','load_my_script');



Что запускать скрипт в нужном месте или нужной странице нужно ставить условие.Например:
function load_my_script(){
    if(is_home){<--------------------
        wp_enqueue_script('magnific-popup',get_template_directory_uri() .
                          '/js/jquery.magnific-popup.min.js',array('jquery'),'',true)
               }

}
add_action('wp_enqueue_scripts','load_my_script');
//------------------------------------------------------

//Подключение стилей в functions.php
function register_styles() {
    wp_register_style('my-bootstrap', get_template_directory_uri() . 
                      '/libs/bootstrap/bootstrap-grid-3.3.1.min.css');
    wp_enqueue_style('my-bootstrap');

    wp_register_style('style', get_template_directory_uri() . 
                      '/style.css');
    wp_enqueue_style('style'); 
    .......

}
add_action( 'wp_enqueue_scripts', 'register_styles' );
//-------------------------------------------------





//ВЫВОД МЕНЮ
*************************************************
    <?php wp_nav_menu(array('theme_location' => 'menu')); ?> - в index.php вместо li

а в functions.php
<?php
register_nav_menu('menu','Main menu')

    Затем создаем меню в админке wordpress
    Поменять на класс .sub-menu  и отредактировать стили

    .current-menu-item a - активны класс в word press






    ----------------------рабочий вариант--------------

    //регистрация меню
    add_action('after_setup_theme', function(){
        register_nav_menus( array(
            'header_menu' => 'Меню в шапке'
        ) );
    });

//убираем лишний div обертку в меню
add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );
function my_wp_nav_menu_args( $args='' ){
    $args['container'] = '';
    return $args;
}

//заменяем классы подменю с уровнями вложенности на свои
class My_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth) {
        $indent = str_repeat("\t", $depth);
        //    $output .= "\n$indent<ul class=\"my-sub-menu\">\n";
        $output .= "\n$indent<ul class=\"menu_sub".$depth."\">\n";
    }
}

//выводим меню на странице
<?php 
    wp_nav_menu( array(
        'menu_class'=>'nav_header',
        'theme_location'=>'header_menu',
        'walker' => new My_Walker_Nav_Menu()

    ) );
?>
--------------------------------------------------------------------------





//======================================================================= 



*************************************************
register_sidebar( $args ) - регистрирует новую панель для виджетов WordPress в пользовательской теме оформления




//ВЫВОД МИНИАТЮР ЗАПИСИ
*************************************************

// ----------   
add_theme_support('post-thumbnails') - регистрация миниатюр (function.php)

set_post_thumbnail_size( $width, $height, $crop ) - устанавливает размер ('600','999',true) миниатюры поста по умолчанию в (function.php)

<?php the_post_thumbnail( $size, $attr ); ?> - выводит html код картинки-миниатюры поста
<!---------------------   -->

<?php   
has_post_thumbnail( $post_id ) - условный тег, проверяющий имеет ли пост картинку миниатюру

    add_theme_support( $feature, $formats ) - позволяет темам или плагинам регистрировать поддержку новых возможностей



    *************************************************
    register_post_type( $post_type, $args ) - создает новый или изменяет имеющийся тип записи

    // МЕТКИ
    *************************************************   
    <?php the_tags('метки', ''); ?> - добавить в single.php вместо html меток.
Сами метки нужно добавить в записи   



<?php 
// КОММЕНТАРИИ
************************************************* 
    Создать comments.php -роль php обработчика. Взять коментарии с другой темы и отредактировать  

    Вот такой отредактированный код из темы  Twenty_Ten: 
<!------------------------------------------------------------------------------------------  -->
    <?php
    /**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to twentyten_comment which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>

<div id="comments">
    <?php if ( post_password_required() ) : ?>
    <p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'twentyten' ); ?></p>
</div><!-- #comments -->
<?php
/* Stop the rest of comments.php from being processed,
         * but don't kill the script entirely -- we still have
         * to fully load the template.
         */
return;
endif;
?>

<?php
// You can start editing here -- including this comment!
?>

<?php if ( have_comments() ) : ?>
<h3 id="comments-title"><?php
    printf( _n( '1 комментарий', '%1$s комментариев', get_comments_number(), 'twentyten' ),
           number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
    ?></h3>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
<div class="navigation">
    <div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'twentyten' ) ); ?></div>
    <div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
</div> <!-- .navigation -->
<?php endif; // check for comment navigation ?>

<ol class="commentlist">
    <?php
    /* Loop through and list the comments. Tell wp_list_comments()
                     * to use twentyten_comment() to format the comments.
                     * If you want to overload this in a child theme then you can
                     * define twentyten_comment() and that will be used instead.
                     * See twentyten_comment() in twentyten/functions.php for more.
                     */
    wp_list_comments( array( 'callback' => 'axxon_comment' ) );
    ?>
</ol>

<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
<div class="navigation">
    <div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'twentyten' ) ); ?></div>
    <div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
</div><!-- .navigation -->
<?php endif; // check for comment navigation ?>

<?php
/* If there are no comments and comments are closed, let's leave a little note, shall we?
     * But we only want the note on posts and pages that had comments in the first place.
     */
if ( ! comments_open() && get_comments_number() ) : ?>
<p class="nocomments"><?php _e( 'Comments are closed.' , 'twentyten' ); ?></p>
<?php endif;  ?>

<?php endif; // end have_comments() ?>

</div><!-- #comments -->

<?php comment_form(); ?>
<!-- -----------------------------------------------------------------  -->



Затем взять код комментариев  для файла functions.php тоже из другой темы.

Здесь код из темы Twenty_Ten:
<!-- ------------------------------------------------------------------------  -->

/**
* Комментарии
*/
if ( ! function_exists( 'twentyten_comment' ) ) :
/**
* Template for comments and pingbacks.
*
* To override this walker in a child theme without modifying the comments template
* simply create your own twentyten_comment(), and that function will be used instead.
*
* Used as a callback by wp_list_comments() for displaying the comments.
*
* @since Twenty Ten 1.0
*/
function axxon_comment( $comment, $args, $depth ) {
$GLOBALS['comment'] = $comment;
switch ( $comment->comment_type ) :
case '' :
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
    <div id="comment-<?php comment_ID(); ?>">
        <div class="comment-author vcard">
            <?php echo get_avatar( $comment, 48 ); ?>
            <?php printf( __( '%s<span class="says"></span>', 'twentyten' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
        </div><!-- .comment-author .vcard -->
        <?php if ( $comment->comment_approved == '0' ) : ?>
        <em class="comment-awaiting-moderation"><?php _e( 'Ваш комментарий ожидает модерации.', 'twentyten' ); ?></em>
        <br />
        <?php endif; ?>

        <div class="comment-meta commentmetadata"><i class="fa fa-clock-o"></i><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
            <?php
            /* translators: 1: date, 2: time */
            printf( __( '%1$s в %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Изменить)', 'twentyten' ), ' ' );
            ?>
        </div><!-- .comment-meta .commentmetadata -->

        <div class="comment-body"><?php comment_text(); ?>
            <div class="reply">
                <?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div><!-- .reply -->
        </div>


    </div><!-- #comment-##  -->

    <?php
    break;
    case 'pingback'  :
    case 'trackback' :
    ?>
<li class="post pingback">
    <p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' ); ?></p>
    <?php
    break;
    endswitch;
    }
    endif;

    /**
* Добавляем древовидной форме комментариев ответить всплывающее окно
*/
    function enqueue_comment_reply() {
        if( is_singular() )
            wp_enqueue_script('comment-reply');
    }
    add_action( 'wp_enqueue_scripts', 'enqueue_comment_reply' );

    /**
* Удаляем поле URL в форме комментариев
*/

    function remove_comment_fields($fields) {

        unset($fields['url']);

        return $fields;

    }

    add_filter('comment_form_default_fields', 'remove_comment_fields');

    //----------------------------------------------------------------------- 

    Затем в файле single.php где должен быть камментарий  пишем функцию:     
    <?php comments_template( $file, $separate_comments ); ?> - Подгружает указанный файл шаблона комментариев для показа комментариев на старице записи. По умолчанию загружает файл: /comments.php из папки темы.




    Затем добавляем стили к нашим коментам. Берем стандартные классы и id вордпресса и изменяем  в своем файле стилей.

    Здесь готовые стили:    

    /* Комментарии */
    #comments {
    clear: both;
    padding-left: 90px;
    margin-top: 40px;
    padding-right: 30px;
    background: #fff none repeat scroll 0 0;
    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05), 0 3px 3px rgba(0, 0, 0, 0.05);
    }

    #comments .navigation {
    padding: 0 0 18px 0;
    }


    h3#comments-title,
    h3#reply-title {
    color: #000;
    font-size: 24px;
    font-weight: 500;
    margin-bottom: 0;
    text-transform: uppercase;
    }
    h3#comments-title {
    padding: 40px 0;
    margin-left: -60px;
    }
    .commentlist {
    list-style: none;
    margin: 0;
    padding: 0;
    }
    .commentlist li.comment {
    line-height: 24px;
    margin: 0 0 24px 0;
    padding: 0;
    position: relative;
    }
    .commentlist li:last-child {
    border-bottom: none;
    margin-bottom: 0;
    }
    #comments .comment-body ul,
    #comments .comment-body ol {
    margin-bottom: 18px;
    }
    #comments .comment-body p:last-child {
    margin-bottom: 6px;
    }
    #comments .comment-body blockquote p:last-child {
    margin-bottom: 24px;
    }
    .commentlist ol {
    list-style: decimal;
    }


    .commentlist .avatar {
    position: absolute;
    left: -60px;
    top: 0;
    width: 48px;
    height: 48px;
    -webkit-border-radius: 100%;
    border-radius: 100%; 
    }

    .children li .comment-author {
    padding-top: 20px;
    }


    .comment-author cite {
    color: #444444;
    font-style: normal;
    font-family: "OpenSansBold", sans-serif;
    }
    .comment-author .says {
    font-style: italic;
    }
    .comment-meta {
    font-size: 12px;
    margin: 0;
    }

    .comment-body p {
    padding-left: 0px;
    margin: 0;
    font-size: 14px;
    }

    .comment-meta a:link,
    .comment-meta a:visited {
    color: #cdcdcd;
    text-decoration: none;
    padding-left: 4px;
    font-size: 12px;
    }
    .comment-meta a:active,
    .comment-meta a:hover {
    color: #4A4A4A;
    }

    .comment-meta {
    color: #CDCDCF;
    }


    .commentlist .odd {

    } 

    .reply {
    padding-bottom: 24px;
    padding-left: 0px;
    padding-top: 15px;
    }

    .reply a {
    font-size: 12px;
    background: #aaaaaa;
    color: #fff;
    padding: 1px 9px;
    -webkit-transition: all .4s;
    -o-transition: all .4s;
    transition: all .4s;
    font-family: "OpenSansBold", sans-serif;
    }

    .reply a:hover {
    background: #505050;
    color: #fff;
    text-decoration: none;
    }

    a.comment-edit-link {
    color: #888;
    }

    a.comment-edit-link:hover {
    color: #4A4A4A;
    }
    .commentlist .children {
    list-style: none;
    margin: 0;
    padding: 0;
    }
    .commentlist .children li {
    border: none;
    margin: 0;
    padding-left: 50px;
    position: relative;
    }

    .comment-body { 
    border-bottom: 1px solid #e7e7e7;
    }

    .commentlist .children p {
    margin: 0;
    padding-left: 0px;
    }

    .commentlist .children .reply {
    padding-left: 0px;
    }

    .commentlist .children li .avatar {
    height: 40px;
    left: 0;
    position: absolute;
    top: 22px;
    width: 40px;
    }

    .nopassword,
    .nocomments {
    display: none;
    }
    #comments .pingback {
    border-bottom: 1px solid #e7e7e7;
    margin-bottom: 18px;
    padding-bottom: 18px;
    }

    .commentlist li {
    padding-left: 60px;
    }

    .commentlist li.comment+li.pingback {
    margin-top: -6px;
    }
    #comments .pingback p {
    color: #888;
    display: block;
    font-size: 12px;
    line-height: 18px;
    margin: 0;
    }
    #comments .pingback .url {
    font-size: 13px;
    font-style: italic;
    }

    .fn {
    font-size: 16px;
    }

    .fn span {
    font-size: 20px;
    }

    .fn a {
    color: #7A7A7A;
    font-size: 18px;
    font-family: "OpenSansBold", sans-serif;
    }

    .fn a:hover {
    color: #000;
    text-decoration: none;
    }

    .comment-awaiting-moderation {
    color: #333;
    font-size: 14px;
    }




    /* Comments form */
    input[type="submit"] {
    color: #333;
    }
    #respond {
    margin-bottom: 70px;
    margin-right: 0;
    padding: 0 30px;
    margin-top: 40px;
    overflow: hidden;
    position: relative;
    border-radius: 5px;
    background: #fff;
    box-shadow: 0 1px 0 rgba(0, 0, 0, 0.05), 0 3px 3px rgba(0, 0, 0, 0.05);
    }
    #respond p {
    margin: 0;
    font-size: 14px;
    }
    #respond .comment-notes  {
    margin-bottom: 15px;
    font-size: 12px;
    color: #999;
    }

    #respond .comment-notes span {
    font-size: 12px;
    }


    #respond .comment-notes p {
    font-size: 14px;
    }


    .form-allowed-tags {
    line-height: 1em;
    }
    .commentlist li.comment #respond {
    margin-top: 10px;
    }

    .commentlist li.comment #respond textarea {
    padding-top: 2px;
    padding-left: 8px;
    }

    .commentlist li.comment #email-notes, .commentlist li.comment #respond .comment-notes {
    display: none;
    }

    .commentlist li.comment #respond h3#reply-title {
    font-size: 18px;
    margin-bottom: 15px;
    }

    .commentlist li.comment #respond .comment-notes {
    margin-bottom: 15px;
    }

    .children #respond .form-submit input {
    font-size: 16px;
    }

    .commentlist li.comment #respond .form-submit input {
    font-size: 16px;
    } 

    h3#reply-title {
    margin: 0;
    padding-top: 20px;
    }
    #comments-list #respond {
    margin: 0 0 18px 0;
    }
    #comments-list ul #respond {
    margin: 0;
    }

    #cancel-comment-reply-link {
    font-size: 12px;
    font-weight: normal;
    line-height: 18px;
    float: right;
    color: #aaa;
    }

    #cancel-comment-reply-link:hover {
    color: #FF8100;
    }

    #respond .required {
    color: #e8554e;
    }

    #respond label {
    color: #565656;
    font-size: 16px;
    font-family: "OpenSansBold", sans-serif;
    }

    #respond span {
    font-size: 20px;
    }

    #respond input {
    margin: 5px 0 9px;
    width: 100%;
    height: 45px;
    font-size: 16px;
    padding-left: 10px;
    color: #666666;
    border: 1px solid #eaeaea;
    background: #f2f2f2;
    }

    #respond input:hover {

    }


    #respond textarea {
    width: 100%;
    border: 1px solid #eaeaea;
    font-size: 16px;
    padding-left: 10px;
    padding-top: 5px;
    color: #666666;
    height: 200px;
    background: #f2f2f2;
    margin-top: 5px;
    }

    #respond input:focus { 
    border: 1px solid #CDCDCD; 
    background: #f9f9f9;
    -webkit-transition: all .7s;
    -o-transition: all .7s;
    transition: all .7s;
    }

    #respond input:focus:required:invalid, textarea:focus:required:invalid {
    outline: none;
    }

    #respond input:focus:required:valid, 
    textarea:focus:required:valid {
    outline: none;
    }

    #respond textarea:focus { 
    border: 1px solid #CDCDCD; 
    background: #f9f9f9;
    -webkit-transition: all .7s;
    -o-transition: all .7s;
    transition: all .7s;
    }		

    #respond textarea:hover {

    }



    #respond .form-allowed-tags {
    color: #888;
    font-size: 12px;
    line-height: 18px;
    }
    #respond .form-allowed-tags code {
    font-size: 11px;
    }
    #respond .form-submit {
    margin: 12px 0;
    }
    #respond .form-submit input {
    font-size: 16px;
    width: auto;
    font-family: "OpenSansBold", sans-serif;
    padding: 0 18px;
    background: #2c2c2c;
    color: #fff;
    border-radius: 4px;
    border: 1px solid #2c2c2c;
    margin-top: 10px;
    }

    #respond .form-submit input:hover {
    border: 1px solid #FF8100;
    background: #FF8100;
    -webkit-transition: all .7s;
    -o-transition: all .7s;
    transition: all .7s;
    }		


    #respond .logged-in-as {
    font-size: 16px;
    }

    #respond .logged-in-as a {
    font-size: 14px;
    color: #323232;
    }

    #respond .logged-in-as a:hover {
    color: #FF8100;
    }




    <?php 
    //ВЫВОД ПОСТОВ
    *************************************************
        Цикл WP
        <?php if (have_posts()) :  while (have_posts()) : the_post(); ?>
    <!-- здесь формирование вывода постов, -->
    <!-- где работают теги шаблона относящиеся к the loop -->
    <?php endwhile; ?>
    <?php endif; ?>





    <!--ПОИСК-->
    *************************************************
    нужно создать фаил  search.php

    <?php $allsearch = &new WP_Query("s=$s&showposts=-1"); $key = wp_specialchars($s, 1); $count = $allsearch->post_count; _e('');  echo $count . ' '; _e('результатов'); wp_reset_query(); ?><span> для <?php echo get_search_query(); ?></span> -Код выводящий количество результатов поиска и запрос поиска php


    <?php the_search_query() ?> - выводит поисковый запрос, который был сделан пользователям. То есть выводит фразу которую искал пользователь. Можно вставлять(<input id="s" value="<?php the_search_query(); ?>" name="s" />)

    <?php include(TEMPLATEPATH . './searchform.php'); ?> - подключение файла searchform.php (там находится форма поиска) к файлу index.php или header.php или к footer.php, там где она была изночально




    <!--ПАГИНАЦИЯ-->
    *************************************************
    <?php previous_post_link($format, $link, $in_same_cat = false, $excluded_categories = ''); ?> - выводит ссылку на предыдущий пост, в параметрах ставим('%link')

    <?php next_post_link($format, $link, $in_same_cat, $excluded_categories); ?> - выводит ссылку на следующий пост, в параметрах ставим('%link')

    <?php posts_nav_link(); ?> - постраничная навигация

    <!----------------------------------------->
    WP-PageNavi- плагин для пагинации
    <?php wp_pagenavi(); ?> - функция плагина, записать в index.php вместо кода html погинации. Затем к активному классу применить стили wp-pagtnavi a{} и   wp-pagtnavi span{}
    <!----------------------------------------->




    когда заканчиваются  посты, чтобы не было постого места вместо "предыдущая запись" или "следующая запись"
    <!---------------------------------------------->
    <div class="nav_previous">
        <span>Предыдущая запись</span>
        <?php if( get_adjacent_post(false, '', true) ) { 
    previous_post_link('<p>%link</p>');
}
        else { 
            $first = new WP_Query('posts_per_page=1&order=DESC');
            $first->the_post();
            echo '<a href="' . get_permalink() . '"><p>Первая запись</p></a>';
            wp_reset_postdata();
        };  ?>
    </div>

    <div class="nav_next">
        <span>Следующая запись</span>
        <?php if( get_adjacent_post(false, '', false) ) { 
    next_post_link('<p>%link</p>');
}
        else { 
            $last = new WP_Query('posts_per_page=1&order=ASC');
            $last->the_post();
            echo '<a href="' . get_permalink() . '"><p>Последняя запись</p></a>';
            wp_reset_postdata();
        };  ?>
    </div>
    <!------------------------------------------------------>








    <!--РЕГИСТРАЦИЯ САЙДБАРА-->
    *************************************************
    register_sidebar( array(
    'name'          => 'Sidebar',
    'id'            => 'sidebar',
    'before_widget' => '<li class="widget">',
        'after_widget'  => '</li>',
'before_title'  => '<h2>',
    'after_title'   => '</h2>',
) ); - регистрация сайдбара в functions.php



<?php if(!dynamic_sidebar('sidebar')): ?>
<div class="sidebar_my">
    <h3>Виджеты сайдбара</h3>
</div>
<?php endif?> - добавляем в sidebar.php




=====================================================
//обрезание описания рубрик в админке сайта 


function wph_trim_cats() {
add_filter('get_terms', 'wph_truncate_cats_description', 10, 2);
}
function wph_truncate_cats_description($terms, $taxonomies) {
if('category' != $taxonomies[0])
return $terms;
foreach($terms as $key=>$term) {
$terms[$key]->description = mb_substr($term->description, 0, 80);
if($term->description != '') {
$terms[$key]->description .= '...';
}
}
return $terms;
}
add_action('admin_head-edit-tags.php', 'wph_trim_cats');


=====================================================





*************************************************
<?php single_cat_title(''); ?> - выводит название рубрики(категории) 

<?php single_tag_title(''); ?> - выводит название меток

<?php the_permalink(); ?> - выводит ссылку на текущий пост

<?php the_cotegory(); ?> - выводит ссылку на рубрики к которым принадлежит пост

the_author() - выводит имя автора поста

<?php the_time('J M Y'); ?> - выводит на экран время (дату) публикации текущего поста

comments_popup_link( $zero, $one, $more, $css_class, $none ) - ссылка на комментарии

<?php the_title( $before, $after, $echo ); ?> - выводит на экран или возвращает заголовок записи(поста)

<?php comments_link(); ?> - Выводит на ссылку (URL) на форму комментирования текущей статьи.

<?php comments_number('0','1','%'); ?> - Выводит общее число комментариев статьи (включая уведомления и пинги).     

the_excerpt() - выводит отрывок (цитату) поста, со вставкой в конец [...]

<?php the_content( $more_link_text, $strip_teaser ); ?> -('далее ') выводит контент текущего поста

comments_template($file, $separate_comments) - подгружает файл шаблона комментариев



<?php get_header(); ?> - подключает файл шаблона header.php

<?php get_sidebar();?> - подключает файл шаблона sidebar.php

<?php get_footer(); ?> - подключает файл шаблона footer.php


<!--ДОБАВЛЕНИЯ ФОТО В ВИДЖЕТ-->
1)Image Widget, Swifty Image Widget by WPGens(мне помог этот), About Me Image Widget  - один из способов добавления картинки в виджет- это с помощью плагинов.  если нужно , то правим еще стили.
2) Сделать запись в нее добавить фотку и код фотки вывести в виджете
3) Загрузить фотки в wordpress  и там ссылку к фотки втавить в виджет



<!--ПЛАГИН САРТИРОВКИ ЗАПИСЕЙ-->
Post Types Order 


<!--ПЛАГИН ДЛЯ ДОБАВЛЕНИЯ ВСПЛЫВАШЕК НА КАРТИНКИ-->
Simple Lightbox



<!--ПЛАГИН ДЛЯ ВЫВОДА CONTACT FORM 7 ВО СВПЛЫВАЮЩЕМ ОКНЕ-->
Easy Modal
или
<!--ВЫВОДИТ ШОРТКОД CONTACT FORM 7 В ШАБЛОНЕ-->
<?php echo do_shortcode('[contact-form-7 id="169" title="Контактная форма"]'); ?>


<!--ОБНАВЛЕНИЯ JQUERY В WP ПЛАГИНОМ-->
jQuery Updater




<!--ОПТИМИЗАЦИЯ СКОРОСТИ ЗАГРУЗКИ-->
<!--===============================================================-->
<!--для отладки сайта на ошибки-->
а)В wp_config.php ВКЛЮЧИТЬ define('WP_DEBUG', true);
б)Установить плагин query monitor

<!--   включаем браузерное кэширование в .htaccess-->
Вариант 1

<ifModule mod_headers.c>
    <FilesMatch "\.(js|css|txt)$">
        Header set Cache-Control "max-age=604800"
    </FilesMatch>
    <FilesMatch "\.(flv|swf|ico|gif|jpg|jpeg|png)$">
        Header set Cache-Control "max-age=2592000"
    </FilesMatch>
    <FilesMatch "\.(pl|php|cgi|spl|scgi|fcgi)$">
        Header unset Cache-Control
    </FilesMatch>
    </IfModule>


Вариант 2

<ifModule mod_expires.c>
    ExpiresActive On
    ExpiresDefault "access plus 5 seconds"
    ExpiresByType image/x-icon "access plus 1 month"
    ExpiresByType image/jpeg "access plus 4 weeks"
    ExpiresByType image/png "access plus 30 days"
    ExpiresByType image/gif "access plus 43829 minutes"
    ExpiresByType application/x-shockwave-flash "access plus 2592000 seconds"
    ExpiresByType text/css "access plus 604800 seconds"
    ExpiresByType text/javascript "access plus 604800 seconds"
    ExpiresByType application/javascript "access plus 604800 seconds"
    ExpiresByType application/x-javascript "access plus 604800 seconds"
</ifModule>



<!--   включаем сжатие в .htaccess-->
<IfModule deflate_module>
    <IfModule filter_module>
        AddOutputFilterByType DEFLATE text/plain text/html
        AddOutputFilterByType DEFLATE text/xml application/xml application/xhtml+xml application/xml-dtd
        AddOutputFilterByType DEFLATE application/rdf+xml application/rss+xml application/atom+xml image/svg+xml
        AddOutputFilterByType DEFLATE text/css text/javascript application/javascript application/x-javascript
        AddOutputFilterByType DEFLATE font/otf font/opentype application/font-otf application/x-font-otf
        AddOutputFilterByType DEFLATE font/ttf font/truetype application/font-ttf application/x-font-ttf
    </IfModule>
</IfModule>
<!--------------------------------------------------------------------->

<!--для сжатия изображения плагин-->
imsanity (сразу режет,сжимает и загружает в WP картинки размера которого мы установили в настройках)

<!--для сжатия установленых картинок-->
EWWW image optimizer, WP Smush(50 картинок лимит)

<!--оптимизация (сжатие,скоращение html) js,html,css-->
autoptimize-плагин сжимает и собирает в один фаил

<!--КЕШИРОВАНИЕ-->
Hyper Cashe
WP fastest cache
Comet Cache
w3 total cache
<!--и удаления кеша-->
WP Super Cache(используем)- после его утановки может ломаться карта сайта sitemap.xml, для этого нужно добавить ее в исключение. Для этого в строчке 'Допустимые имена & Запрещенные адреса' во втором поле пишем sitemap.xml или sitemap_index.xml 


<!--================================================================-->

<!--=======================================================-->
<!--ФАИЛ ROBOTS.TXT ДЛЯ WP-->
Пример файла robots.txt

User-agent: *
Disallow: /cgi-bin
Disallow: /wp-admin
Disallow: /trackback
Disallow: */trackback
Disallow: */*/trackback
Disallow: */*/feed/*/
Disallow: */feed

User-agent: Yandex
Disallow: /cgi-bin
Disallow: /wp-admin
Disallow: /wp-includes
Disallow: /wp-content/plugins
Disallow: /wp-content/cache
Disallow: /wp-content/themes
Disallow: /trackback
Disallow: */trackback
Disallow: */*/trackback
Disallow: */*/feed/*/
Disallow: */feed
Disallow: /tag
Host: ваше главное зеркало

Sitemap: полный путь к карте сайта
<!--=========================================-->










<!--ПОДКЛЮЧЕНИЕ ДОЧЕРНЕЙ ТЕМЫ-->




<!--подключение стилей дочерней темы-->
Добрый день! Видимо путь не правильно указываете. Лучше использовать другой способ - создать в дочерке functions.php в него  такую функцию
function artabr_child_theme_enqueue_styles () {
$parent_style = 'parent-style';
wp_enqueue_style($parent_style, get_template_directory_uri() . '/style.css');
wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array($parent_style), wp_get_theme()->get('Version'));
}






OptionTree - плагин панели опций, один из самых важных шагов при разработке тем


<!--ПЕРЕВОД ТЕМЫ-->
<!--=====================================-->
<!--ручным способом-->
Poedit -(редактор) переводчик файлов ru_RU.po ru_RU.mo для wp.Что бы работал перевод важно в файле wp-config.php было так  define( 'WPLANG', 'ru_RU' );
<!--с помощью плагина-->
CodeStyling Localization
<!--===================================-->













<!--ВЫВОД КАРТИНОК из админпонели WP с помощью плагина Custom Field Suite-->
<!--Вывод осуществляется с помощью цикла (loop) и создания  в нем два поля с img и text-->
<!--==============================================================================-->
<?php $slider_fields = CFS()->get( 'mainslider' );
if( ! empty($slider_fields) ): ?>
<div class="slider owl-carousel">
    <?php foreach ( $slider_fields as $site_slider ) { ?>
    <div class="slide">
        <div class="slide__image"><img src="<?php echo $site_slider['mainslider_image']; ?>" width="879" height="468" /></div>
        <div class="slide__text"><?php echo __( $site_slider['mainslider_title'],'ortomet'); ?></div>
    </div><!-- .slider -->
    <?php } ?> 
</div>
<?php endif; ?>

<!--мой код отредактированный-->
<div class="slider">

    <div class="owl-carousel">
        <?php  if (have_posts()) : query_posts('cat=6');
        while (have_posts()) : the_post(); ?>

        <?php $slider_fields = CFS()->get( 'main_slider' );?>

        <?php foreach ( $slider_fields as $site_slider ) : ?>

        <div class="slider_item">
            <img src="<?php echo $site_slider['slid']; ?>"/>
        </div>

        <?php endforeach ?> 
        <?php endwhile; ?>
        <?php endif; ?>
    </div>

</div>


<!--===========================================================================-->






============================================================
<!--Сортировка картинок по папкам в библеотеки WP-->
WP Media Categories
Media Library Assistant- плагин
enhanced-media-library -плагин
<!--================================================-->









==================================================================
<!--СЛАЙДЕР НА WP-->

meta slider- плагин

=================================================================








====================================================================================
<!--ДЛЯ УБИРАНИЕ ЛИШНИХ ТЕГОВ-->

Raw HTML - плагин

remove_filter( 'the_content', 'wpautop' ); - отключение фильтра, если сайт с нуля то он подойдет


function filter_ptags_on_images($content){
//функция preg replace, которая убивает тег p
return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
    }
    add_filter('the_content', 'filter_ptags_on_images');
    ===========================================================================================









    =============================================================================
    <!--   ОТКЛЮЧЕНИЕ ОБРЕЗКИ(ИНФОРМАТИРОВАНИЯ) ВОРДПРЕССОМ КОДА В ВИЗУЛЬНОМ РЕДАКТОРЕ-->
    Нужно в functions.php прописать:

    remove_filter('the_content', 'wpautop'); //Отключаем автоформатирование в полном посте

    remove_filter('the_excerpt', 'wpautop'); //Отключаем автоформатирование в кратком (анонсе)  посте

    remove_filter('comment_text', 'wpautop'); //Отключаем автоформатирование в комментраиях

    =====================================================================================









    =====================================================
    // Для  справки: существует 3 спосаба пострение циклов: 
    query_posts()
    WP_Query()
    get_posts() - самый удобный вариант
    ===================================================










    ====================================================================================
    /**
    * Отключаем srcset и sizes для картинок в WordPress
    */

    // Отменяем srcset
    // выходим на раннем этапе, этот фильтр лучше чем 'wp_calculate_image_srcset'
    add_filter('wp_calculate_image_srcset_meta', '__return_null' );

    // Отменяем sizes - это поздний фильтр, но раннего как для srcset пока нет...
    add_filter('wp_calculate_image_sizes', '__return_false',  99 );

    // Удаляем фильтр, который добавляет srcset ко всем картинкам в тексте записи
    remove_filter('the_content', 'wp_make_content_images_responsive' );

    // Очищаем атрибуты из wp_get_attachment_image(), если по каким-то причинам они там остались...
    add_filter('wp_get_attachment_image_attributes', 'unset_attach_srcset_attr', 99 );
    function unset_attach_srcset_attr( $attr ){
    foreach( array('sizes','srcset') as $key )
    if( isset($attr[ $key ]) )    unset($attr[ $key ]);
    return $attr;
    }
    ====================================================================================







    ============================================================================
    rich tex tags - плагин для добавления свойств редактора в раздел описания рубрики

    =================================================================================



    ==================================================================================
    <?php echo get_the_date('d'); ?> -вывод даты (один из вариантов )
    ====================================================================================




    ==================================================================================

    обрезка теста в записи,  то есть замена the_content или  the_excerpt
    /**
    * Обрезка текста (excerpt). Шоткоды вырезаются. Минимальное значение maxchar может быть 22.
    */
    function kama_excerpt( $args = '' ){
    global $post;

    $default = array(
    'maxchar'   => 350,   // количество символов.
    'text'      => '',    // какой текст обрезать (по умолчанию post_excerpt, если нет post_content.
    // Если есть тег <!--more-->, то maxchar игнорируется и берется все до <!--more--> вместе с HTML
    'autop'     => true,  // Заменить переносы строк на <p> и <br> или нет
    'save_tags' => '',    // Теги, которые нужно оставить в тексте, например '<strong><b><a>'
    'more_text' => 'Читать дальше...', // текст ссылки читать дальше
    );

    if( is_array($args) ) $_args = $args;
    else                  parse_str( $args, $_args );

    $rg = (object) array_merge( $default, $_args );
    if( ! $rg->text ) $rg->text = $post->post_excerpt ?: $post->post_content;
    $rg = apply_filters('kama_excerpt_args', $rg );

    $text = $rg->text;
    $text = preg_replace ('~\[/?.*?\](?!\()~', '', $text ); // убираем шоткоды, например:[singlepic id=3], markdown +
    $text = trim( $text );

    // <!--more-->
    if( strpos( $text, '<!--more-->') ){
    preg_match('/(.*)<!--more-->/s', $text, $mm );

    $text = trim($mm[1]);

    $text_append = ' <a href="'. get_permalink( $post->ID ) .'#more-'. $post->ID .'">'. $rg->more_text .'</a>';
    }
    // text, excerpt, content
    else {
    $text = trim( strip_tags($text, $rg->save_tags) );

    // Обрезаем
    if( mb_strlen($text) > $rg->maxchar ){
    $text = mb_substr( $text, 0, $rg->maxchar );
    $text = preg_replace('~(.*)\s[^\s]*$~s', '\\1 ...', $text ); // убираем последнее слово, оно 99% неполное
    }
    }

    // Сохраняем переносы строк. Упрощенный аналог wpautop()
    if( $rg->autop ){
    $text = preg_replace(
    array("~\r~", "~\n{2,}~", "~\n~",   '~</p><br ?/>~'),
    array('',     '</p><p>',  '<br />', '</p>'),
    $text
    );
    }

    $text = apply_filters('kama_excerpt', $text, $rg );

    if( isset($text_append) ) $text .= $text_append;

    return ($rg->autop && $text) ? "<p>$text</p>" : $text;
    }


    ----------
    вывод <?php echo kama_excerpt( array('maxchar'=>200) ); ?>


    ====================================================================================




    =============================================================================
    вывод контента определенной страницы 

    или этот

    $id = 0; // add the ID of the page where the zero is
    $p = get_page($id);
    $t = $p->post_title;
    echo '<h3>'.apply_filters('post_title', $t).'</h3>'; // the title is here wrapped with h3
    echo apply_filters('the_content', $p->post_content);



    или этот
    <?php
    $id = 35; // id страницы
    $post = get_page($id);
    $content = $post->post_content;
    echo $post->post_content;
    ?>
    ==============================================================================


    =============================================================================
    вывод контента определенной записи 

    <?php
    $id = 11; // id записи
    $post = get_post($id);
    $content = $post->post_content;
    echo $post->post_content;
    ?>
    ==============================================================================



    ==================================================================================

    перевести произвольные строчки в плагине  Polylang

    файл.php   -  <?php pll_e('text2'); ?>

    function.php - pll_register_string('text1', 'text2');
    ===================================================================================






    =================================================================================
    Создание шаблона для отдельной записи

    в function.php добавляем
    add_filter('single_template', create_function(
    '$the_template',
    'foreach( (array) get_the_category() as $cat ) {
    if ( file_exists(TEMPLATEPATH . "/single-{$cat->slug}.php") )
    return TEMPLATEPATH . "/single-{$cat->slug}.php"; }
    return $the_template;' )
    );

    и затем создаем фаил  типа single-{cat-slug}.php

    ИЛИ 
    создать файл  типа single-{post-type}-{slug}.php


    =========================================================================================





    =======================================================================
    Вывод подрубрики с указанием количества записей в подрубрики (wp_list_categories)
    <?php
    if (count(get_categories('child_of='.$cat)))
        if (is_category()) {
            $current_cat=get_query_var('cat');
            wp_list_categories('child_of='.$current_cat.'&title_li=&show_count=1&style=none');} ?>

    =========================================================================


    ==========================================================================
    Как вывести записи только родительской рубрики?

    WordPress по умолчанию добавляет записи дочерних рубрик в вывод записей родительской рубрики. Таким образом, вывести записи только родительской рубрики без записей дочерних рубрик нельзя. Данную ситуацию можно исправить с помощью такого кода, добавленного в файл functions.php вашей темы:

    //вывод записей только родительской рубрики start
    function wph_only_parent_category($query) {
    if (!is_admin() && $query->is_main_query() && $query->is_category())
    $query->set('category__in', array(get_queried_object_id()));
    }
    add_action('pre_get_posts', 'wph_only_parent_category');
    //вывод записей только родительской рубрики end
    Данный сниппет будет работать только с основным циклом вывода записей (свои циклы, заданные через query_posts будут игнорироваться). Если вас это не устраивает, то вы можете убрать проверку на основной цикл:

    //вывод записей только родительской рубрики start
    function wph_only_parent_category($query) {
    if (!is_admin() && $query->is_category())
    $query->set('category__in', array(get_queried_object_id()));
    }
    add_action('pre_get_posts', 'wph_only_parent_category');
    //вывод записей только родительской рубрики end



    Этот код будет работать с любыми циклами вывода записей.

    ===============================================================






=======================================================
    минюатюра для рубрик

    Плагин: Category Thumbnails

    Funtions.php: add_theme_support('category-thumbnails');
                    add_image_size($name, $width, $height,  $crop);


    Вывод:<?php                     
            $catID = get_query_var('cat'); 
            $img_info = get_the_category_data($catID); 
            echo  wp_get_attachment_image($img_info->id, 'img_cat');
            ?>
=======================================================================







========================================
<!--активный пункт меню -->
nav li.current-menu-item,
nav li.current-menu-parent,
nav li.current-menu-ancestor{
    color: #76d48f;
}

<!--активный пункт меню в polylang-->
nav li.pll-parent-menu-item{
    color: #fff;
}
=======================================
















==============================================
<!--регистраци произвольных типов записей-->
   function true_register_post_type_init() {
    $labels = array(
        'name' => 'Функции',
        'singular_name' => 'Функцию', // админ панель Добавить->Функцию
        'add_new' => 'Добавить функцию',
        'add_new_item' => 'Добавить новую функцию', // заголовок тега <title>
        'edit_item' => 'Редактировать функцию',
        'new_item' => 'Новая функция',
        'all_items' => 'Все функции',
        'view_item' => 'Просмотр функции на сайте',
        'search_items' => 'Искать функции',
        'not_found' =>  'Функций не найдено.',
        'not_found_in_trash' => 'В корзине нет функций.',
        'menu_name' => 'Кодекс WP' // ссылка в меню в админке
    );
    $args = array(
        'labels' => $labels,
        'public' => true,
        'show_ui' => true, // показывать интерфейс в админке
        'has_archive' => true, 
        'menu_icon' => get_stylesheet_directory_uri() .'/img/function_icon.png', // иконка в меню
        'menu_position' => 20, // порядок в меню
        'supports' => array( 'title', 'editor', 'comments', 'author', 'thumbnail'),
        'hierarchichal' => false,
        'capability_type' => 'post'

    );
    register_post_type('functions', $args);
}


add_action( 'init', 'true_register_post_type_init' ); // Использовать функцию только внутри хука init



=========================================================











убираем лишние теги в contact form 7
<!--==========================================-->

define('WPCF7_AUTOP', false ); - вставить в файл wp-config.php


вставить в function.php
add_filter('wpcf7_form_elements', function($content) {
$content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);
return $content;
}); 
<!--======================================================-->













Обрезка текста (рубрики и др ) на php

<!--=====================================================================-->
                  <?php                   
                            $cat_id = 1;
                            $cat_description = category_description( $cat_id );
                            $cat_description = rtrim($cat_description, "!,.-");
                            $cat_description = strip_tags($cat_description);
                            $cat_description = substr($cat_description, 0, 1500);
                            $cat_description = substr($cat_description, 0, strrpos($cat_description, ' '));
                            echo $cat_description."… ";


                            ?>
<!--=====================================================================================-->



<?php


/-- начало вставки кода для обтекания картинок текстом--/
div.aligncenter {
display: block;
margin-left: auto;
margin-right: auto;
}
.alignleft {
float: left;
margin-right: 5px;
}
.alignright {
float: right;
margin-left: 5px;
}
.wp-caption {
border: 1px solid #ddd;
text-align: center;
background-color: #f3f3f3;
padding-top: 4px;
margin: 10px;
/* optional rounded corners for browsers that support it */
— moz-border-radius: 3px;
— khtml-border-radius: 3px;
— webkit-border-radius: 3px;
border-radius: 3px;
}
trong>margin: 0;
padding: 0;
border: 0 none;
}
.wp-caption p.wp-caption-text {
font-size: 11px;
line-height: 17px;
padding: 0 4px 5px;
margin: 0;
}
/-- конец вставки кода для обтекания картинок текстом--/

?>




