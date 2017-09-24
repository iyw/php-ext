<?php
function my_callback() {
    echo 'hello world';
}

class MyClass
{
    public static function myCallback()
    {
        echo 'hello world';
    }
    public function __invoke()
    {
        echo '__invoke';
    }
}
//type1: simple callback
//call_user_func('my_callback');

//type2: static class method call
//call_user_func(['MyClass', 'myCallback']);

//type3: php5.2.3+
//call_user_func('MyClass::myCallback');

//type4 object method call
//$obj = new MyClass();
//call_user_func([$obj, 'myCallback']);

//type5 relative static class method call (php5.3.0+)
class A {
        public static function who() {
            echo "A\n";
        }
}

class B extends A {
        public static function who() {
            echo "B\n";
        }
}

//call_user_func(array('B', 'parent::who')); // A

//type6 Objects implementing __invoke can be used as callables (since PHP 5.3)
$obj = new MyClass();
call_user_func($obj);


