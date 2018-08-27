<!doctype html>

<html lang="en">
    <head>
        <meta charset="utf-8">

        <title>Search thetvdb</title>
        <meta name="description" content="Search thetvdb">
        <meta name="author" content="SitePoint">
        <link rel="stylesheet" href="css/main.css?v=1.0">
        <script src="js/libs/jquery/jquery-3.3.1.min.js"></script>
        
    </head>

    <body>

        <main>
            <div class="container">
                <!-- Menu Section Start -->
                <header id="home">
                    Super Api and Url stater!
                </header>

                <div class="search-wrapper">
                    <label for="newtab-search-text" class="search-label">
                        <span class="sr-only">
                            <span>Search Tv Tropes</span> 
                        </span>
                    </label>
                    <input type="text" name="tv-show-search" id="tv-show-search" value="" placeholder="Search tvtropes.com" title="Search tvtropes.com"/>
                    <button id="searchSubmit" class="search-button" title="Search" onclick="c.httpSearchTheTvDb()">
                        <span class="sr-only">
                            <span>Search</span>
                        </span>
                    </button>
                </div>            
            </div>
            <div class="row">
                <section class="column">
                    <div class="center"><h2>Show</h2></div>
                    <div id="about-series">
                        <!-- About -->
                    </div>
                </section>
                <section class="column">
                    <div class="center"><h2>Episodes</h2></div>
                    <article id="episodes">
                        <!-- Episodes -->
                    </article>
                </section>
            </div>
        </main>
        <hr />

        <div id="imdb-link-container" class="center">
            Search using the address box.
        </div>

        <script id="template-imdb-link-container" type="text/template">
            Click Here to open the IMDB <a href="https://www.imdb.com/title/{{imdbId}}/" target="_blank" rel="nofollow" id="imdb-link">{{seriesName}}</a> page.
        </script>

        <script id="template-about-series" type="text/template">
                <div class="card-show-container">
                    <div class="card-show" >
                        <img src="https://www.thetvdb.com/banners/{{banner}}" alt="{{slug}}" class="w100">
                        <div class="card-show-container x-center">
                        <p><h4>{{seriesName}}</h4></p>
                        <p><strong>Description - </strong>{{overview}}</p>
                        <p><strong>Network - </strong> {{network}}</p>    
                    </div>
                </div>
        </script>
        
        <script id="template-episodes" type="text/template">
             <article class="episodes-box" >
                            <h3 style="clear: right;">{{episodeName}}</h3>  
                            <p>
                                <img src="https://www.thetvdb.com/banners/{{filename}}" 
                                     alt="{{episodeName}}"
                                     style=""
                                     />
                                {{overview}}
                            </p>
                        </article>
         </script>       
        
        

        <script>
            /**
             * 
             * TODO: refine abstract into reusable parts
             * 
             */
            
            $( document ).ready(function() {
                
                $urlArg = $(location).attr("href").split('/').pop();
                $('#tv-show-search').val($urlArg)
                c.httpSearchTheTvDb()
            
            });
            
            /**
             * url make pretty
             * 
             * @param {string} title
             * @param {string} url
             * @returns {undefined}
             */
            function UpdateUrl(title, url) {
                if (typeof (history.pushState) != "undefined") {
                    var obj = {Title: title, Url: url};
                    history.pushState(obj, obj.Title, obj.Url);
                } else {
                    alert("Browser does not support HTML5.");
                }
            }

            $('#tv-show-search').on('keypress', function (e) {
                if (e.which === 13) {

                    //Disable textbox to prevent multiple submit
                    $(this).attr("disabled", "disabled");

                    c.httpSearchTheTvDb();

                    //Enable the textbox again if needed.
                    $(this).removeAttr("disabled");
                }
            });


            class common {

                constructor($) {
                    this.$ = $;
                    this.id = '';
                    this.searchPhrase;
                    this.oSearch;
                    this.oSeriesInfo;
                    this.oSerieslist;
                }

                httpSearchTheTvDb() {
                    var _this = this;
                    let searchValue = $('#tv-show-search').val().trim();

                    if ('' === searchValue)
                        return;

                    this.constructor.searchPhrase = searchValue;

                    let searchUrl = '/search/' + searchValue;

                    var settings = {
                        "async": true,
                        "url": searchUrl,
                        "method": "GET",
                        "dataType": "json"
                    }
                    let searchPhrase = this.constructor.searchPhrase;
                    $.ajax(settings).done(
                            function (response) {
                                // var obj = $.parseJSON(response);
                                //obj.data.id
                                _this.id = response.id;
                                _this.oSearch = response;
                                _this.fillTheTemplate('about-series', response);

                                UpdateUrl(searchPhrase.charAt(0).toUpperCase(), searchPhrase)
                                _this.httpRequestTheTvDbSeries();
                                _this.httpRequestTheTvDbSeriesInfo();
                            }).fail(function (response) {
                        console.log('call failed'+response);
                    });

                }

                httpRequestTheTvDbSeriesInfo() {
                    var _this = this;
                    let id = this.constructor.id;

                    if ('' === id)
                        return;

                    let url = '/series/info/' + id;

                    var settings = {
                        "async": true,
                        "url": url,
                        "method": "GET",
                        "dataType": "json"
                    }

                    $.ajax(settings)
                            .done(function (response) {
                                _this.fillTheTemplate('imdb-link-container', response);
                            })
                            .fail(function (response) {
                                console.log('call failed' + response);
                            });
                }
                
                /**
                 * 
                 * @returns {undefined}
                 */
                httpRequestTheTvDbSeries() {
                    var _this = this;
                    let id = this.constructor.id;

                    if ('' === id)
                        return;

                    let url = '/series/episodes/' + id;

                    var settings = {
                        "async": true,
                        "url": url,
                        "method": "GET",
                        "dataType": "json"
                    }

                    $.ajax(settings).done(
                            function (response) {
                                _this.fillTheTemplate('episodes', response.data);
                            }).fail(function (response) {
                        console.log('call failed => '+response);
                    });

                }

                /**
                 * this is the template handler
                 * 
                 * @param {type} targetId
                 * @param {type} data
                 * @param {type} maxlength
                 * @returns {undefined}
                 */
                fillTheTemplate(targetId, data, maxlength = 3) {
                    var tmp = 'template-' + targetId;
                    var template = $('#' + tmp);
                    var templateInnerHtml = template.html();
                    var html = "";

                    //scrape template for values create an array

                    var regex = /{{(.*?)}}/g;
                    var found = templateInnerHtml.match(regex).map(function (val) {
                        return val.replace(/{{/g, '').replace(/}}/g, '');
                    });

                    //console.log(found);

                    //check if data is array
                    if (data.Error) {
                        $('#' + targetId).html(
                                '<div style="color:red;">' + data.Error + '</div>'
                                );
                        return;
                    } else {
                        if (!Array.isArray(data)) {
                            data = (data.data)? [data.data]: [data];
                        }
                        for (let i = 0; i < data.length; i++) {
                            html += templateInnerHtml;
                            for (let n = 0; n < found.length; n++) {
                                console.log(data[i][found[n]]);
                                console.log(found[n]);
                                html = html.replace("{{" + found[n] + "}}", data[i][found[n]]);
                            }
                            if (i >= maxlength-1) {
                                break;
                            }
                        }
                    }

                    $('#' + targetId).html(html);
                }

                get id() {
                    return this.constructor.id;
                }

                set id(val) {
                    this.constructor.id = val;
                }

                get searchPhrase() {
                    return this.constructor.searchPhrase;
                }

                set searchPhrase(val) {
                    this.constructor.searchPhrase = val;
                }

            }

            var c = new common(jQuery);

        </script>
    </body>
</html>