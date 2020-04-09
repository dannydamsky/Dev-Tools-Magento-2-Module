DannyDamsky DevTools Extension
=====================
A collection of utilities meant to improve the experience of developing modules for Magento
without breaking existing functionality.

-----
- version: 1.0.0
- extension key: DannyDamsky_DevTools

Requirements
------------
- PHP >= 7.3.0
- Magento_Config
- Magento_Eav
- Magento_Cron
- PHP Extension - JSON
- PHP Extension - Imagick
- PHP Extension - SimpleXML
- PHP Extension - ZLib

Suggestions
------------
- Twig: Version 2.0 and above, enables the use of twig templates in Magento.

Compatibility
-------------
- Magento >= 2.3.3

Features
-----------------

This module adds useful functionality to Magento 2
without the breaking backwards compatibility or reducing
performance in any way that is significant.

This module's features include:

1. Twig Template Support - Include the "twig/twig" package in your composer.json
and you can start writing all of your templates using Twig, with support for
most (if not all) of the functions you normally use in Magento 2 PHTML templates,
and including access to template variables such as "block".

2. An extension to the Magento frontend and backend template (block) classes.
This extension enables the use of callable variables in your PHTML template,
so for example instead of typing:

        <?php echo $block->escapeHtml("YOUR_STRING_HERE") ?>
    
    You can now use the following in your code:
    
        <?php echo $escapeHtml("YOUR_STRING_HERE") ?>
        
    Usable variables include: $escapeHtml, $escapeHtmlAttr, $escapeJs, $escapeCss, $escapeUrl, and $trans (For translations).
    
    For a full list please take a look at:
        
        DannyDamsky\DevTools\Traits\BlockTrait::_beforeToHtml()
        
    To enable this functionality your block class must extend either of the two classes:
    
    Backend:
    
        DannyDamsky\DevTools\App\Backend\Block\Template
        
    Frontend:
    
        DannyDamsky\DevTools\View\Element\Template
        
    <b>These blocks also include helper methods for generating the x-magento-init scripts.</b>
        
3. A slight (but important) improvement to the Magento 2 ImageMagick adapter.
The default Magento 2 implementation does not configure ImageMagick to generate
[progressive JPEGs](https://www.liquidweb.com/kb/what-is-a-progressive-jpeg/), 
this module enables this behaviour.

4. Improvements to Magento 2's JSON serializer. PHP's json_decode function isn't
good at decoding JSONs with lots of unicode and special characters.
This module adds an override to the following function:

        Magento\Framework\Serialize\Serializer\Json::unserialize

    The override attempts to run the above function, but in case of failure
    relies on an that algorithm has proven to decode more complicated JSON strings.
    
5. You no longer have to include <b>registration.php</b> in your modules.
This module is configured to automatically load any module that does
not include this file.
Modules found in the vendor and app/code folders are supported.

6. Helper LESS mixins included in the _module.less file of this module
for adding CSS properties with prefixes for browsers.
Prefixes include: 

    - lib-dannydamsky-devtools-prefix-align-items, 

    - lib-dannydamsky-devtools-prefix-align-content,
    
    - lib-dannydamsky-devtools-prefix-align-self,
    
    - lib-dannydamsky-devtools-prefix-justify-content,
    
    - lib-dannydamsky-devtools-prefix-justify-self
    
    - lib-dannydamsky-devtools-prefix-user-select
    
    For a full list please take a look at:
    
        view/base/web/css/source/_module.less
        
7. Facade classes support

    To create a facade, you must extend the following class:
    
        DannyDamsky\DevTools\Model\Facade

    By default, this class expects you to put your facade class in:
    
        VendorName\ModuleName\Facade\NAMESPACE_RELATIVE_TO_HELPER\YourFacade

    Where <b>NAMESPACE_RELATIVE_TO_HELPER</b> is the relative namespace
    of the helper class that you want to bind the Facade to.
    
    For example, if you want to create a facade for:
    
        DannyDamsky\PlayGround\Helper\Person
        
    Your facade class needs to have the namespace:
    
        DannyDamsky\PlayGround\Facade\Person
        
    And then, any public methods that are accessible from your helper method,
    will be automatically accessible in your Facade class as static methods.
    
    In order to override the namespacing behaviour of a Facade class,
    you may add a constant that equals that class name of the helper that you wish to bind
    the Facade to.
    
    For Example:
    
        <?php
        
        namespace DannyDamsky\PlayGround\Facade;
        
        class Logger extends \DannyDamsky\DevTools\Model\Facade
        {
            public const FACADE_HELPER = \DannyDamsky\PlayGround\Logger\Logger::class;
        }
        
    This code sample binds the Logger Facade to a Logger implementation.
    
8. An addition of numerous global functions including:

    - booltostr - Convert a boolean to its strict equivalent.
    
    - prettify - Convert a string such as "someText_here" to "Some Text Here"
    
    - time_millis - Returns the current time in milliseconds
    
    - escape_double_quotes_in_json_string - Escapes double quotes in a JSON string.
    
    - convert_to_utf8 - Converts a unicode encoded string to UTF-8
    
    - compress_image_for_web - Uses ImageMagick to compress an image very efficiently with a minimal impact on image quality.
    
    - optimize_image_basic - A performance-light variant of compress_image_for_web, best used with small images.
    
    - get_trace_as_string - Get the exception trace as string, works the same as the "Throwable::getTraceAsString()" method
    but does not truncate any of the data.
    
    - str_shorten - Allows shortening of strings to a specified limit.
    
    - is_associative - Returns whether an array is an associative array.
    
    - str_contains - Returns whether a string (the haystack) contains another string (the needle),
    variations of this function include: str_icontains (case-insensitive variant),
    mb_str_contains (multi-byte variant) and mb_str_icontains (multi-byte case-insensitive variant).
    
    - get_module_name - Accepts a classname and returns the name of the module of the given class.
    
    - rglob - Same as the "glob" function, but searches for files recursively in all child folders.
    
    - gzcompress_file - Accepts a source file and compresses it into a GZIP-compressed file.
    
    - rm_rf - Recursive force-delete function.
    
    - run_in_transaction - Helper function for handling a single transaction run using Magento's
    ResourceModel/Database adapter classes.

9. Improved database migration classes.
This module includes the following abstract classes which
can be extended by your module's setup folder classes:

        DannyDamsky\DevTools\Model\Setup\InstallData
        
        DannyDamsky\DevTools\Model\Setup\InstallSchema
        
        DannyDamsky\DevTools\Model\Setup\UpgradeData
        
        DannyDamsky\DevTools\Model\Setup\UpgradeSchema
        
        DannyDamsky\DevTools\Model\Setup\Uninstall
        
    All of these classes have one method for you to implement: <i>execute()</i>
    
    This method takes no arguments, the <b>$setup</b> and <b>$context</b> arguments you are used to
    still exist as protected class properties <b>$_setup</b> and <b>$_context</b>.
    The <b>$_connection</b> class property also exists, and is equal to the return value
    from <i>$setup->getConnection()</i>.
    
    If you are used to running:
    
        $setup->startSetup()
        
    And:
    
        $setup->endSetup()
        
    There is no longer a need to do that, these methods are run before and after
    the <i>execute()</i> method respectively.
    
    Class Specific Features:
    
    Install/Upgrade Schema Classes:
     - Access to the $_schema class property,
     which is used for writing Laravel-Like database migration operations.
     - Built-in version-control support.

    Install/Upgrade Data Classes:
    - Built-in version-control support.
    
    Uninstall Class:
    - Accessible <i>dropTable()</i> class method.
    
    <h4>The Schema Class Property:</h4>
    
    This property is used for writing Laravel-Like database migration operations.
    It is worth mentioning that while the code syntax is similar to Laravel,
    the definitions used are completely Magento-specific and if you've
    worked with Magento 2 Install/Upgrade Schema classes
    you should be able to recognize the methods available
    here quite easily.
    
    To create a table you can run <i>$this->_schema->newTable(...)</i>
    
    To alter a table you can run <i>$this->_schema->alterTable(...)</i>
    
    Or you can just run <i>$this->_schema->table(...)</i> and let the schema
    property decide if the table should be altered or created 
    (by checking if the table exists in the first place).
    
    Either of the 3 functions mentioned above accept the same arguments,
    the first one is the table name, and the second one is a callable
    that accepts a $blueprint as an argument.
    
    Below is a code sample showing how to use this property:
    
        $this->_schema->table('dannydamsky_playground_random_table', 
            function (\DannyDamsky\DevTools\Api\BlueprintInterface $blueprint) {
                $blueprint->bigint('entity_id', true)
                    ->setIdentity(true)
                    ->setNullable(false)
                    ->setPrimary(true)
                    ->setComment('Entity ID');
                $blueprint->text('some_text', 255)
                    ->setNullable(true);
            }
        );
        $this->_schema->addIndex('dannydamsky_playground_random_table', 'some_text'); 

    This code creates a table called "dannydamsky_playground_random_table",
    with an "entity_id" column and a "some_text" column. It also sets an index
    on the "some_text" column after the table has been created.
    
    For further information please take a look at these interfaces:
    
    The $_schema property:
    
        DannyDamsky\DevTools\Api\SchemaInterface
        
    The $blueprint argument:    
        
        DannyDamsky\DevTools\Api\BlueprintInterface
        
    The return value from column function calls of the $blueprint argument:    
        
        DannyDamsky\DevTools\Api\ColumnDefinitionInterface

    <h4>The Built-In Version-Control Support:</h4>
    
    The Install/Upgrade Data/Schema classes provide version control support.
    
    Which means that you are not required to use the abstract <i>execute()</i>
    method, instead you can leave it empty and create methods for every version of the
    module (or you can use them both), by creating methods with the following naming scheme:
    
        public function __vMODULE_VERSION_WITH_UNDERSCORES_INSTEAD_OF_DOTS() 
        {
            // Your Migration Here
        }
        
    The code in the abstract Install/Upgrade Data/Schema class' <i>install()</i> and </i>upgrade()</i>
    will know to run these methods, and will know to compare their versions to your current module version.
    
    Here's an example of such a method:
    
        public function __v1_0_4()
        {
            $this->_schema->table('dannydamsky_playground_random_table', 
                function (\DannyDamsky\PlayGround\Api\BlueprintInterface $blueprint) {
                    $blueprint->text('some_text')
                        ->setNullable(false)
                        ->setDefault('')
                        ->setComment('Some Text');
                }
            );
        }
        
    <h4> Here is sample UpgradeData class from the DannyDamsky\PlayGround sample module:</h4>
    
        <?php
        
        namespace DannyDamsky\PlayGround\Setup;
        
        use DannyDamsky\DevTools\Api\BlueprintInterface;
        use DannyDamsky\DevTools\Model\Setup\UpgradeSchema as ExtendedUpgradeSchema;
        use Throwable;
        
        final class UpgradeSchema extends ExtendedUpgradeSchema
        {
            /**
             * @inheritDoc
             */
            public function execute(): void
            {
                //
            }
        
            /**
             * @throws Throwable
             */
            public function __v1_0_3(): void
            {
                $this->_schema->table('dannydamsky_playground_random_table', static function (BlueprintInterface $blueprint): void {
                    $blueprint->bigint('entity_id', true)
                        ->setIdentity(true)
                        ->setNullable(false)
                        ->setPrimary(true)
                        ->setComment('Entity ID');
        
                    $blueprint->text('some_text', 255)
                        ->setNullable(true);
                });
            }
        
            /**
             * @throws Throwable
             */
            public function __v1_0_4(): void
            {
                $this->_schema->table('dannydamsky_playground_random_table', static function (BlueprintInterface $blueprint): void {
                    $blueprint->text('some_text')
                        ->setNullable(false)
                        ->setDefault('')
                        ->setComment('Some Text');
                });
            }
        }

10. Log rotation support (Configurable in admin)

    Enables [log rotation](https://en.wikipedia.org/wiki/Log_rotation) functionality from inside Magento 2,
    with support for features such as moving log each day to a separate
    directory with a timestamp as its name, compressing log files with GZIP, and
    deleting the log files after a configured number of days.
    
    This feature must be enabled in the admin configuration. (See the configuration section for more details)

11. JS polyfills support (Configurable in admin)

    Enables loading a JS polyfills script to your website,
    which enables the use of the most-used ES5, ES6 and ES7
    functions for all browsers.
    
    In order to do that, it uses the [Polyfill.IO](https://polyfill.io) API, which
    can be changed to something else in the admin configurations.
    
    Polyfill.IO is particularly good because it gives different results to different browsers, so
    newer browser versions will not have a noticeable performance impact from loading the polyfills.
    
    This feature must be enabled in the admin configuration. (See the configuration section for more details)

Configurations
---------------

All of this module's configurations are located in the Magento 2 admin page 
in the route: 

Stores -> Configurations -> Advanced -> Developer 
(These configurations only show up when you're in developer mode)

The configurations in this route that belong to this module include:

1. <b>Debug</b> - Enable Printing of Twig Generated Templates
2. <b>Template Settings</b> - Auto-Recompile Twig Templates
3. <b>Template Settings</b> - Twig Strict Variables
4. <b>Template Settings</b> - Twig Template Charset
5. <b>JavaScript Settings</b> - Enable JS Polyfills
6. <b>JavaScript Settings</b> - Polyfill API URL (Depends on JS polyfills being enabled)
7. <b>Caching Settings</b> - Cache Twig Templates
8. <b>Log Rotation Settings</b> - Enabled
9. <b>Log Rotation Settings</b> - Compress (Depends on log rotations being enabled)
10. <b>Log Rotation Settings</b> - Days (Depends on log rotations being enabled)

Developer
---------
Danny Damsky <dannydamsky99@gmail.com>

Licence
-------
Copyright (c) 2020 Danny Damsky

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:
1. Redistributions of source code must retain the above copyright
   notice, this list of conditions and the following disclaimer.
2. Redistributions in binary form must reproduce the above copyright
   notice, this list of conditions and the following disclaimer in the
   documentation and/or other materials provided with the distribution.
3. Neither the name of the author nor the names of its contributors may
   be used to endorse or promote products derived from this software

THIS SOFTWARE IS PROVIDED BY THE AUTHOR AND CONTRIBUTORS ``AS IS'' AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR OR CONTRIBUTORS BE LIABLE
FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS
OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY
OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF
SUCH DAMAGE.
