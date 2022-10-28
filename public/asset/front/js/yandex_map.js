ymaps.ready(initMap);

function initMap() {
    // Стоимость за километр.
    var DELIVERY_TARIFF = 20,
        // Минимальная стоимость.
        MINIMUM_COST = 500,
        myMap = new ymaps.Map('map', {
            center: [current_latitude, current_longitude],
            zoom: 9,
            controls: []
        }),
        // Создадим панель маршрутизации.
        routePanelControl = new ymaps.control.RoutePanel({
            options: {
                showHeader: false,
                title: 'Delivery calculation'
            }
        }),
        zoomControl = new ymaps.control.ZoomControl({
            options: {
                size: 'small',
                float: 'none',
                position: {
                    bottom: 145,
                    right: 10
                }
            }
        });
    // Пользователь сможет построить только автомобильный маршрут.
    routePanelControl.routePanel.options.set({
        types: {auto: true}
    });

    // var suggestView1 = new ymaps.SuggestView('origin-input');
    // var suggestView2 = new ymaps.SuggestView('destination-input', {provider: provider, results: 3});
    // if(suggestView1) {
    //     console.log(suggestView1);
    // }


    myMap.controls.add(zoomControl).add(routePanelControl);

    routePanelControl.routePanel.getRouteAsync().then(function (route) {

        // Зададим максимально допустимое число маршрутов, возвращаемых мультимаршрутизатором.
        route.model.setParams({results: 1}, true);

        // Повесим обработчик на событие построения маршрута.
        route.model.events.add('requestsuccess', function () {

            var activeRoute = route.getActiveRoute();

            if(route.getActiveRoute()) {
                $('#yandex_estimated_btn').show();
            } else {
                $('#yandex_estimated_btn').hide();
            }
            console.log(route.getActiveRoute());
            if (activeRoute) {
                // Получим протяженность маршрута.
                var length = route.getActiveRoute().properties.get("distance");
                var duration = route.getActiveRoute().properties.get("duration");
                    // Вычислим стоимость доставки.
                    price = calculate(Math.round(length.value / 1000));
                    // Создадим макет содержимого балуна маршрута.
                //     balloonContentLayout = ymaps.templateLayoutFactory.createClass(
                //         '<span>Расстояние: ' + length.text + '.</span><br/>' +
                //         '<span style="font-weight: bold; font-style: italic">Стоимость доставки: ' + price + ' р.</span>');
                // // Зададим этот макет для содержимого балуна.
                // route.options.set('routeBalloonContentLayout', balloonContentLayout);
                // Откроем балун.
                $('input[name="duration"]').val(duration.text);
                $('input[name="distance"]').val(length.value);
                $('input[name="seconds"]').val(duration.value);
                activeRoute.balloon.open();
            }
        });

    });
    function calculate(routeLength) {
        return Math.max(routeLength * DELIVERY_TARIFF, MINIMUM_COST);
    }

}



function setMarker(address) {

    ymaps.geocode(address, {
        results: 1
    }).then(function (res) {

        // Выбираем первый результат геокодирования.
        var firstGeoObject = res.geoObjects.get(0),
            // Координаты геообъекта.
            coords = firstGeoObject.geometry.getCoordinates();
        console.log(firstGeoObject.geometry.getCoordinates());
            // Область видимости геообъекта.
            bounds = firstGeoObject.properties.get('boundedBy');

        firstGeoObject.options.set('preset', 'islands#darkBlueDotIconWithCaption');
        // Получаем строку с адресом и выводим в иконке геообъекта.
        firstGeoObject.properties.set('iconCaption', firstGeoObject.getAddressLine());

        // Добавляем первый найденный геообъект на карту.
        myMap.geoObjects.add(firstGeoObject);
        // Масштабируем карту на область видимости геообъекта.
        myMap.setBounds(bounds, {
            // Проверяем наличие тайлов на данном масштабе.
            checkZoomRange: true
        });

    });
}