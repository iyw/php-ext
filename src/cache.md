##缓存规范

####1. 冷热数据分离，不要将所有数据全部都放到Redis中
    虽然Redis支持持久化，但是Redis的数据存储全部都是在内存中的，成本昂贵。建议根据业务只将高频热数据存储到Redis中，对于低频冷数据可以使用MySQL/ElasticSearch/MongoDB等基于磁盘的存储方式，不仅节省内存成本，而且数据量小在操作时速度更快、效率更高！

####2. 不同的业务数据要分开存储
    不要将不相关的业务数据都放到一个Redis实例中，建议新业务申请新的单独实例。因为Redis为单线程处理，独立存储会减少不同业务相互操作的影响，提高请求响应速度；同时也避免单个实例内存数据量膨胀过大，在出现异常情况时可以更快恢复服务！

####3. 存储的Key一定要设置超时时间
    如果应用将Redis定位为缓存Cache使用，对于存放的Key一定要设置超时时间！因为若不设置，这些Key会一直占用内存不释放，造成极大的浪费，而且随着时间的推移会导致内存占用越来越大，直到达到服务器内存上限！另外Key的超时长短要根据业务综合评估，而不是越长越好

####4. 对于必须要存储的大文本数据一定要压缩后存储
    对于大文本【超过500字节】写入到Redis时，一定要压缩后存储！大文本数据存入Redis，除了带来极大的内存占用外，在访问量高时，很容易就会将网卡流量占满，进而造成整个服务器上的所有服务不可用，并引发雪崩效应，造成各个系统瘫痪！

####5. 线上Redis禁止使用Keys正则匹配操作
    Redis是单线程处理，在线上KEY数量较多时，操作效率极低【时间复杂度为O(N)】，该命令一旦执行会严重阻塞线上其它命令的正常请求，而且在高QPS情况下会直接造成Redis服务崩溃！如果有类似需求，请使用scan命令代替！

    缓存的键长度不超过 48 字节

    缓存值不能超过 60 兆字节

    压缩后的缓存值不能超过 1M

   1. 键
        缓存的键长不超过48字节
   2. 值
        缓存的值不超过60M字节,压缩后不超过1M
   3. 超时时间
        一定要设置超时时间
   4. 使用场景