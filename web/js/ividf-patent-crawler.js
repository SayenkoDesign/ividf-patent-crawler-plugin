var $ = jQuery;
$(document).ready(function()
{
    // sort by data in selecot.
    // use DESC as second arg to order in desc order
    function sortText(selector, order) {
        var mylist = $('#patents');
        var listitems = mylist.children(".patent").get();

        listitems.sort(function(a, b) {
            var compA = $(a).find(selector).text().toUpperCase();
            var compB = $(b).find(selector).text().toUpperCase();

            if(order === "DESC") {
                return (compA < compB) ? 1 : (compA > compB) ? -1 : 0;
            } else {
                return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
            }
        });

        $.each(listitems, function(index, item) {
            mylist.append(item);
        });
    }

    // sort by data in selecot.
    // use DESC as second arg to order in desc order
    function sortDate(selector, order) {
        var mylist = $('#patents');
        var listitems = mylist.children(".patent").get();

        listitems.sort(function(a, b) {
            var dateA = $(a).find(selector).text().toUpperCase().split(" ").reverse();
            var dateB = $(b).find(selector).text().toUpperCase().split(" ").reverse();

            dateA[1] = convertMonthToDigit(dateA[1]);
            dateB[1] = convertMonthToDigit(dateB[1]);

            var compA = dateA.join();
            var compB = dateB.join();

            if(order === "DESC") {
                return (compA < compB) ? 1 : (compA > compB) ? -1 : 0;
            } else {
                return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
            }
        });

        $.each(listitems, function(index, item) {
            mylist.append(item);
        });
    }

    function convertMonthToDigit(month) {
        switch(month) {
            case "JAN": return "01";
            case "FEB": return "02";
            case "MAR": return "03";
            case "APR": return "04";
            case "MAY": return "05";
            case "JUN": return "06";
            case "JUL": return "07";
            case "AUG": return "08";
            case "SEP": return "09";
            case "OCT": return "10";
            case "NOV": return "11";
            case "DEC": return "12";
        }
    }

    $('#patent_controls select').on('change', function() {
        if($(this).val() == ".applied_on" || $(this).val() == ".issued_on") {
            sortDate($('#patent_controls select[name=sort]').val(), $('#patent_controls select[name=dir]').val());
        } else {
            sortText($('#patent_controls select[name=sort]').val(), $('#patent_controls select[name=dir]').val());
        }
        showFirstRows();
    });

    // show 10 at a time
    function showFirstRows() {
        $('.vc_row.patent').hide();
        $('.vc_row.patent').not('.hidden').slice(0,10).show();
    }
    showFirstRows();

    // reveal more patents as user scrolls towards bottom
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
            $('.vc_row.patent').not(':visible').not('.hidden').slice(0,10).show();
        }
    });

    // case insensitve contains
    $.extend($.expr[':'], {
        'containsi': function(elem, i, match, array)
        {
            return (elem.textContent || elem.innerText || '').toLowerCase()
                    .indexOf((match[3] || "").toLowerCase()) >= 0;
        }
    });

    // filter patents on submit
    $('#patent_controls').on('submit', function(e){
        var term = $('#patent_controls input').val();
        $('.vc_row.patent:containsi("' + term + '")').removeClass('hidden');
        $('.vc_row.patent:not(:containsi("' + term + '"))').addClass('hidden');
        showFirstRows();

        e.preventDefault();
        return false;
    });
});