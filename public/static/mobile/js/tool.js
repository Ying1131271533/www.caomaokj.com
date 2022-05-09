/**
 * 函数节流
 * @param func 目标函数
 * @param delay 延迟时间
 * @param options options.leading：是否在刚触发后立即执行目标函数
 *                options.trailing：是否在停止触发后还执行一次目标函数
 */
function throttleTool (func, delay, options) {
    var timer;
    var previous = 0; // 上一次执行的时间点
    options = options || {leading:false,trailing:true};
    delay = delay || 500;
    var _throttle = function () {
        var context = this;
        var args = arguments;
        var now = +new Date();
        // 如果leading为false，则设置上一次执行的时间点为当前，自然也就不会立马执行目标函数了
        if (!previous && options.leading === false) {
            previous = now;
        }
        // 计算距离上次执行的时间点还剩多少时间
        var remaining = delay - (now - previous);
        // 如果剩余时间小于等于0，说明从上次执行到当前已经超过了设定的delay间隔
        // 如果剩余时间大于设定的delay，说明当前系统时间被修改过
        if (remaining <= 0 || remaining > delay) {
            if (timer) {
                clearTimeout(timer);
                timer = null;
            }
            func.apply(context, args);
            previous = now;
        } else if (!timer && options.trailing === true) {
            timer = setTimeout(function(){
                // 如果leading为false代表下次触发后不需要立马执行目标函数，所以设置为0，在17行才会顺利进入判断
                previous = options.leading === false ? 0 : +new Date();
                func.apply(context, args);
                timer = null;
            }, remaining); //计算距离上次执行的时间点剩余的时间，使用setTimeout保证了即使后续不触发也能再执行一次目标函数
        }
    }

    _throttle.cancel = function () {
        clearTimeout(timer);
        timer = null;
        previous = 0;
    }
   return _throttle;
  }