<?php
/**
 * Created by PhpStorm.
 * User: sz
 * Date: 17.02.15
 * Time: 10:26
 */

namespace frontend\widgets\TimeCalendar;


use yii\web\View;

class DatePicker extends \kartik\date\DatePicker
{

    public $values = [];

    public function registerAssets()
    {
        if ($this->disabled) {
            return;
        }
        parent::registerAssets();

        DatePickerAsset::register($this->getView());
        $this->getView()->registerJs('
            $(".datepicker").on("click", function(e){
                var $target = $(e.target).closest("span, td, th"),
                    year, month, day,
                    $dp = $(this);
                if ($target.length === 1) {
                    //console.log($dp.parent("div"));
                    var datapicker = $dp.parent("div").data().datepicker;
                    var format = "ddmmyyyy";
                    var dates = datapicker.getFormattedDate(format);
                    dates = dates.split(",");
                    if (dates.length == 5) {
                        $dp.find("td.day").not(".active").addClass("disabled");
                    }
                    switch ($target[0].nodeName.toLowerCase()) {
                        case "th":
                            $dp = $(this).closest(".datepicker");
                            break;
                        case "td":
                            if ($target.is(".day") && !$target.is(".disabled")) {
                                day = parseInt($target.text(), 10) || 1;
                                year = datapicker.viewDate.getUTCFullYear();
                                month = datapicker.viewDate.getUTCMonth();
                                var curDate = new Date(Date.UTC.apply(Date, [year, month, day]));
                                var curr_date = curDate.getDate();
                                curr_date = curr_date.toString().length > 1 ? curr_date : "0" + curr_date;
                                var curr_month = curDate.getMonth() + 1;
                                curr_month = curr_month.toString().length > 1 ? curr_month : "0" + curr_month;
                                var curr_year = curDate.getFullYear();

                                curDate = curr_date + "" + curr_month + "" + curr_year;
                                validDate = curr_date + "." + curr_month + "." + curr_year;
                                if ($($target[0]).hasClass("active")) {
                                    $(this).parent().find("." + curDate).remove();
                                } else {
                                    if (dates.length <= 5) {
                                        var el =
                                            "<div class=\"date_block " + curDate + "\" data-date=\"" + validDate + "\">" +
                                            "<span class=\"item_block\">" +
                                            "<input type=\"text\" name=\"dateCalendar[" + validDate + "][]\"> " +
                                            "<span class=\"remove\" style=\"display:none\">&times;</span>" +
                                            "</span>" +
                                            "<span class=\"add add_datepicker\">+</span>" +
                                            "<span class=\"remove_date\">Удалить дату</span>" +
                                            "</div>";
                                        $dp.after(el);
                                    }
                                }
                            }
                            break;
                    }
                }
            });',
            View::POS_READY
        );
    }

} 