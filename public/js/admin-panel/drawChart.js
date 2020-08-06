"use strict"

function drawChart(staistics, staistics2, staistics3, texts) {
// Create the data table.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Дата');
    data.addColumn('number', texts.count);
    data.addRows(staistics.data);

    var data2 = new google.visualization.DataTable();
    data2.addColumn('string', 'Страна');
    data2.addColumn('number', 'Количество');
    data2.addRows(staistics2.data);

    var data3 = google.visualization.arrayToDataTable([
        ['', 'Telegram', 'Viber'],
        [texts.users_count, statistics3.data.Telegram, statistics3.data.Viber]
    ]);

    // var data4 = google.visualization.arrayToDataTable([
    //     ['', 'Без доступа', 'Платный', 'Бесплатный'],
    //     ['Кол. пользователей', staistics4.no, staistics4.paid, staistics4.free]
    // ]);

// Set chart options
    var options = {
        'title':texts.count_users_visits,
        'width':'100%',
        'height':300,
        'colors':['#3c8dbc']
    };

    var options2 = {
        'title':texts.count_users_country,
        'width':'100%',
        'height':300
    };

    var options3 = {
        'title':texts.count_users_messengers,
        'width':'100%',
        'height':300,
        'colors':['#0088cc', '#665CAC']
    };

    // var options4 = {
    //     'title':'Доступ',
    //     'width':'100%',
    //     'height':300,
    //     'colors':['#3c8dbc', '#fed134', '#1cac5b']
    // };

// Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
    chart.draw(data, options);

    var chart2 = new google.visualization.PieChart(document.getElementById('chart_div_2'));
    chart2.draw(data2, options2);

    var chart3 = new google.visualization.BarChart(document.getElementById('chart_div_3'));
    chart3.draw(data3, options3);

    // var chart4 = new google.visualization.BarChart(document.getElementById('chart_div_4'));
    // chart4.draw(data4, options4);


//             let response = JSON.parse(data);
//
//
//             // Create the data table.
//             var data = new google.visualization.DataTable();
//             data.addColumn('string', 'Страна');
//             data.addColumn('number', 'Количество');
//             data.addRows(response.countUsersCountries);
//
// // Create the data table.
//             var data2 = new google.visualization.DataTable();
//             data2.addColumn('string', 'Дата');
//             data2.addColumn('number', 'Количество');
//             data2.addRows(response.countVisits);
//
// // Set chart options
//             var options = {'title':'Количество пользователей по странам (Всего: '+response.countUsersAll+')',
//                 'width':'100%',
//                 'height':300};
//
// // Set chart options
//             var options2 = {'title':'Количество посещений, за последние '+response.countDays+' дней',
//                 'width':'100%',
//                 'height':300,
//                 'colors':['#3c8dbc']};
//
// // Instantiate and draw our chart, passing in some options.
//             var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
//             chart.draw(data, options);
//
//             var chart2 = new google.visualization.ColumnChart(document.getElementById('chart_div2'));
//             chart2.draw(data2, options2);
//
//
//         }
//     });



}

function drawChartAnalizeMailingLog(data) {
    // Create the data table.
    var data2 = google.visualization.arrayToDataTable([
        ['City', texts.mailing_successfully, texts.mailing_not_successful],
        [texts.mailing_count_messages, data.true, data.false]
    ]);

    // Set chart options
    var options = {'title':texts.mailing_messages_sent+data.all,
        'width':'100%',
        'height':300,
        'colors':['#3c8dbc', '#FF0000']
    };

    // Instantiate and draw our chart, passing in some options.
    /*var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, options);
    */
    var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
    chart.draw(data2, options);
}
