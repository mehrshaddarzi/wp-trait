<?php

namespace WPTrait\Utils;

trait Singleton
{
    /** 
    * Collection of instance.
    * @var self|array 
    */
    private static $instance = [];

    
	 * This method returns new or existing Singleton instance
	 * of the class for which it is called. This method is set
	 * as final intentionally, it is not meant to be overridden.
	 *
	 * @return object Singleton instance of the class.
	 */
	final public static function instance(): self
     {
		/**
		 * If this trait is implemented in a class which has multiple
		 * sub-classes then static::$_instance will be overwritten with the most recent
		 * sub-class instance. Thanks to late static binding
		 * we use get_called_class() to grab the called class name, and store
		 * a key=>value pair for each `classname => instance` in self::$_instance
		 * for each sub-class.
		 */
		$called_class = get_called_class();

		if ( ! isset( $instance[ $called_class ] ) ) {

			$instance[ $called_class ] = new $called_class();

			/**
			 * Dependent items can use the `singleton_init_{$called_class}` hook to execute code
			 */
			do_action( sprintf( 'singleton_init_%s', $called_class ) ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores, WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound

		}

		return $instance[ $called_class ];

	}
    private function __construct()
    {
        // Intentionally empty
    }

    private function __clone()
    {
        // Intentionally empty
    }
}
