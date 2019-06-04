/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function() {
    $.validator.addMethod("phoneCode", function(value, element) {
        var length = value.length;
        var regexp = /^(13)([0-9]){9}|(14)([0-9]){9}|(15)([0-9]){9}|(18)([0-9])|(17)([0-9]){9}$/;
        return   this.optional(element) || (length == 11 && regexp.test(value));
    }, " 电话号码不正确");
    $.validator.addMethod("userName", function(value, element) {
//        var regexp = new RegExp("^([\\u4E00-\\u9FA5\\uF900-\\uFA2D]){2,6}|([a-zA-Z0-9]|[_]|[-]){4,25}$");
        var regexp = /^[a-zA-Z0-9]{4,20}?$/;
        return   this.optional(element) || (regexp.test(value));
    }, "用户名输入错误");
    //利率必须大于0
    $.validator.addMethod("reqApr", function(value, element) {
        if (!(value * 1 > 0) || value * 1 > 20) {
            return false;
        }
        var regexp = /^([1-9][\d]{0,1}|0)(\.[\d]{1,2})?$/;
        return   this.optional(element) || (regexp.test(value));
    }, "利率错误");
    //利率验证可以为0
    $.validator.addMethod("Apr", function(value, element) {
        var regexp = /^([1-9][\d]{0,1}|0)(\.[\d]{1,2})?$/;
        return   this.optional(element) || (regexp.test(value));
    }, "利率错误");
    $.validator.addMethod("DoubleMoney", function(value, element) {
        if (value * 1 < 1) {
            return false;
        }
        var regexp = /^([1-9][\d]{0,8}|0)(\.[\d]{1,2})?$/;
        return   this.optional(element) || (regexp.test(value));
    }, "两位小数");
    //金额必填没有小数
    $.validator.addMethod("IntMoney", function(value, element) {
        if (value * 1 < 1) {
            return false;
        }
        var regexp = /^([1-9][\d]{0,8}|0)?$/;
        return   this.optional(element) || (regexp.test(value));
    }, "金额为大于0正整数");
    //整数
    $.validator.addMethod("Number", function(value, element) {
        var regexp = /^([1-9][\d]{0,8}|0)?$/;
        return   this.optional(element) || (regexp.test(value));
    }, "金额整数");
    //金额，非必填   
    $.validator.addMethod("dateFormat", function(value, element) {
        var regexp = /^(\d{4})-(\d{2})-(\d{2})$/;
        return   this.optional(element) || (regexp.test(value));
    }, "日期格式不正确");
});

