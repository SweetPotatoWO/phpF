/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(function() {
    $(".butReturn").click(function() {
        $Util.returnRefresh();
    });
    rulevalid();
    $(".butSaveRule").click(function() {
        var isOK = $("#form-prob").valid();
        if (isOK) {
            saveRule();
        }
    });
});


function saveRule() {
    var data = {};
    data.probSubject = $("#probSubject").val();
    data.probID = $("#probID").val();
    data.sort = $("#sort").val();
    data.probOption = $("input[name='option']").serializeArray();//选项
    data.probFraction = $("input[name='fraction']").serializeArray(); //分数
    data.probAsr = $("input[name='remark']").serializeArray();//问题描述
    $.ajax({
        type: "post",
        url: "/Backend/Problem/saveProb",
        data: {'par': data},
        dataType: "json",
        beforeSend: function(XMLHttpRequest) {
            $(".sub-content").showLoading();
        },
        success: function(data, textStatus) {
            $(".sub-content").hideLoading();
            if (data.status == 1) {
                $("#hidAction").val("edit");
                $win.confirm(data.msg + ",是否关闭窗口？").on(function() {
                    $('.butReturn').trigger("click");
                });
            } else {
                $win.warn(data.msg);
            }
        },
        complete: function(XMLHttpRequest, textStatus) {
            $(".sub-content").hideLoading();
        },
        error: function() {
            $(".sub-content").hideLoading();
        }
    });
}

/**
 * 新增子规则
 * @param {type} type
 */
function addCondition() {
    var singleInfo = "";
    singleInfo += '<label class="col-xs-2 control-label" for="inputInfo"></label>'
            + '<div class="col-xs-10 form-inline">'
            + '           选项 '
            + ' <input type="text" class="form-control"  name="option"  maxlength="8"  style="width:40px;" > '
            + ' 分数 '
            + ' <input type="text" class="form-control"  name="fraction"  maxlength="8"  style="width:50px;" >  '
            + ' 问题描述 '
            + ' <input type="text" class="form-control"  name="remark"  style="width:230px;" >  '
            + ' <a class="btn btn-warning  delete" onclick="delCondition(this);" ><span class="icon-remove"></span>删除</a>'
            + '</div>';
    $("#single div").last().after(singleInfo);
}
/**
 *删除子规则 
 * @param {type} obj
 */
function delCondition(obj) {
    $(obj).parent().prev().remove();
    $(obj).parent().remove();
}

function rulevalid() {
    $("#form-prob").validate({
        rules: {
            probSubject: {required: true},
            remark: {required: true},
            option: {required: true},
            fraction: {required: true},
        },
        messages: {
            probSubject: {required: "问卷题目不能为空！"},
            remark: {required: "问题描述不能为空！"},
            option: {required: "选项不能为空"},
            fraction: {required: "分数不能为空"}
        }
    });
}


