#1.php基础
php数据类型
- 标量类型
boolean
integer
float
string
- 复合类型
 array
 object
- 特殊类型
  resource
  NULL

##1. 自动转换成false的类型
false, int 0, float 0.0, '', "0", [], null, 空标记生成的SimpleXml对象（使用empty判断）

null, 变量未申明 使用isset判断

##2. float
- 不要相信浮点数的精度，不要轻易把浮点转化成int型，不要直接比较

```
$f1 = 0.1;
$f2 = 0.7;
var_dump(($f1+$f2) * 10); //float(8)
var_dump((int)(($f1+$f2)*10)); //float(7)

//正确的方式
var_dump(round($f1+$f2, 2)*10);//float(8)
var_dump((int)round($f1+$f2, 2)*10); int(8)

```
##3. String
- 四种表示方式
 1. ''
 2. ""
 3. heredoc 
 4. nowdoc

##4.NULL
- 3种情况会被认为是NULL
  1. 被赋值为NULL
  2. 申明但是没有赋值
  3. 被unset()

#.callback
传递6种方式：
```php
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

```

####应用：自动加载

```php
<?php
class Register
{
        public static function autoload($class)
        {
             $autoload = str_replace('\\', '/', $class);
             require $autoload.'.php';
        }
}

spl_autoload_register(['Register', 'autoload']);

$obj = new \src\Autoload();
$obj->test();

```
## 魔术方法

## 继承 封装 多态

## 静态方法 & 实例方法

## 延迟静态绑定 & 延迟动态绑定


