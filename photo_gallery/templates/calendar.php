
<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=" crossorigin="anonymous"></script>

<style type="text/css">
    table {
        font-family: arial;
        font-size: 13px;
        color: #222;
        border-left: 1px solid #e1e1e1;
        border-top: 1px solid #e1e1e1;
    }
    th {
        border-bottom: 2px solid #e1e1e1;
        height: 50px;
        padding: 8px;
        border-right: 1px solid #e1e1e1;
    }
    td {
        border-bottom: 1px solid #e1e1e1;
        height: 50px;
        padding: 8px;
        text-align: center;
        border-right: 1px solid #e1e1e1;
    }
    td:hover {
        background-color: #fffcb5;
    }
    td.available {

    }
    td.booked {
        background-color: green;
    }
    td.taken {
        background-color: #f1f1f1;
    }
    td.timeLeft {
        vertical-align: top;
        text-align: right;
        text-transform: uppercase;
    }
    span.block {
        display: block;
        margin-top: 1px;
    }
    .weak {
        font-size: 11px;
        color: #555;
    }
    #nextLast {
        font-size: 18px;
        line-height: 42px;
        font-family: arial;
        width: 100%;
        color: #888;
    }
</style>

<div style="clear:both;"></div>

<div id="nextLast">
    <div style="float:right;">
        <a href="%link%?year=%nextYear%&start=%nextWeek%">Later &raquo;</a>
    </div>
    <div style="float:left;">
        <a href="%link%?year=%lastYear%&start=%lastWeek%">&laquo; Earlier</a>
    </div>
    <div style="text-align:center;">All times in EST.</div>
    <div style="clear:both;"></div>
</div>

<table width="100%" cellspacing=0 cellpadding=0>
    <thead>
    <tr>
        <th width="7%">&nbsp;</th>
        <th width="13.29%">Mon %day1%</th>
        <th width="13.28%">Tue %day2%</th>
        <th width="13.29%">Wed %day3%</th>
        <th width="13.28%">Thu %day4%</th>
        <th width="13.29%">Fri %day5%</th>
        <th width="13.28%">Sat %day6%</th>
        <th width="13.29%">Sun %day7%</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        %cells%
    </tr>
    </tbody>
</table>

<script type="text/javascript">

    function bookSpot(slot)
    {
        window.location = '%link%/scheduler/' + slot + '?slot=' + slot;
    }


    $("td.available").hover(
        function() {
            var id = $(this).attr('id');
            var res = id.substr(8, 4);
            $(this).html('book ' + res);
        }, function() {
            $(this).html('');
        }
    );

</script>
