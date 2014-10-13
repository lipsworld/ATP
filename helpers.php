<?php namespace GM\ATP;

function get_container( $values = [ ] ) {
    static $container = NULL;
    if ( is_null( $container ) ) {
        $container = new Container( $values );
    }
    return $container;
}

function ajax_callback() {
    $cont = get_container();
    $loader = $cont[ 'loader' ];
    $provider = $cont[ 'cache.provider' ];

    /**
     * No caching when WP_DEBUG is true, but can be filtered via "ajax_template_cache" hook.
     * If external object cache is active use that (via Transients API) otherwise
     * use Stash, by default with FileSystem driver, but driver and its options can be customized
     * via "ajax_template_cache_driver" and "ajax_template_{$driver}_driver_conf" filter hooks.
     */
    if ( $provider->isEnabled() && $cont[ 'cache.handler' ] instanceof Cache\HandlerInterface ) {
        $provider->setHandler( $cont[ 'cache.handler' ] );
        $loader->setCacheProvider( $provider );
    }

    $loader->getData();

    exit();
}
