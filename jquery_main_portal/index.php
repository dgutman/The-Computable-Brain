<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="css/smoothness/jquery-ui-1.10.3.custom.min.css" />
        <link rel="stylesheet" type="text/css" media="screen" href="css/style.css" />

        <script src="js/jquery-1.9.0.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>

        <script>
            var project_obj = new Object();
            var project = null;
            var experiment_obj = new Object();
            var experiment = null;
            var underlay_obj = new Object();
            var underlay = null;
            var overlay_obj = new Object();
            var overlay = null;
            var atlaslist_obj = new Object();

            $(document).ready(function() {
                var url = 'fetch.php?type=project';
                $.ajax({
                    type: "GET",
                    url: url,
                    async: false,
                    success: function(responseData, textStatus, jqXHR) {
                        project_obj = JSON.parse(responseData);
                    },
                    error: function(responseData, textStatus, jqXHR) {
                        alert("error " + textStatus);
                    }
                });

                for (var i = 0; i < project_obj.length; i++) {
                    var newhtml = '<li class="ui-widget-content" id="' + project_obj[i]['project_group'] + '">' + project_obj[i]['short_project_descr'] + '</li>';
                    $('#project').append(newhtml);
                }
            });
            $(function() {
                $("#tabs-min").tabs();
                $('#project').selectable({
                    tolerance: 'fit',
                    stop: function(event, ui) {
                        $(event.target).children('.ui-selected').not(':first').removeClass('ui-selected');
                        $('#experiment').empty();
                        $('#underlay').empty();
                        $('#overlay').empty();
                        $('#atlaslist').empty();
                        $("#overlay-drop").empty();
                        $("tr[id^='control-']" ).remove();
                        experiment_obj = new Object();
                        underlay_obj = new Object();
                        overlay_obj = new Object();
                        atlaslist_obj = new Object();
                        project = $(event.target).children('.ui-selected')[0].id;

                        var url = 'fetch.php?type=experiment&project_group=' + project;
                        $.ajax({
                            type: "GET",
                            url: url,
                            async: false,
                            success: function(responseData, textStatus, jqXHR) {
                                experiment_obj = JSON.parse(responseData);
                            },
                            error: function(responseData, textStatus, jqXHR) {
                                alert("error " + textStatus);
                            }
                        });

                        for (var i = 0; i < experiment_obj.length; i++) {
                            var newhtml = '<li class="ui-widget-content" id="' + experiment_obj[i]['experiment_group'] + '">' + experiment_obj[i]['image_space_description'] + '</li>';
                            $('#experiment').append(newhtml);
                        }
                    }
                });

                $('#experiment').selectable({
                    tolerance: 'fit',
                    stop: function(event, ui) {
                        $(event.target).children('.ui-selected').not(':first').removeClass('ui-selected');
                        $('#underlay').empty();
                        $('#overlay').html('<li class="ui-state-default ui-state-disabled">This Experiment Contains no overlays</li>');
                        $('#atlaslist').empty();
                        $("#overlay-drop").empty();
                        $( "tr[id^='control-']" ).remove();
                        underlay_obj = new Object();
                        overlay_obj = new Object();
                        atlaslist_obj = new Object();
                        experiment = $(event.target).children('.ui-selected')[0].id;

                        var url = 'fetch.php?type=underlay&project_group=' + project + '&experiment_group=' + experiment;
                        $.ajax({
                            type: "GET",
                            url: url,
                            async: false,
                            success: function(responseData, textStatus, jqXHR) {
                                underlay_obj = JSON.parse(responseData);
                            },
                            error: function(responseData, textStatus, jqXHR) {
                                alert("error " + textStatus);
                            }
                        });

                        for (var i = 0; i < underlay_obj.length; i++) {
                            var newhtml = '<li class="ui-widget-content" id="' + underlay_obj[i]['underlay_image_id'] + '">' + underlay_obj[i]['underlay_description'] + '</li>';
                            $('#underlay').append(newhtml);
                        }

                        var url = 'fetch.php?type=overlay&project_group=' + project + '&experiment_group=' + experiment;
                        $.ajax({
                            type: "GET",
                            url: url,
                            async: false,
                            success: function(responseData, textStatus, jqXHR) {
                                overlay_obj = JSON.parse(responseData);
                            },
                            error: function(responseData, textStatus, jqXHR) {
                                alert("error " + textStatus);
                            }
                        });

                        if (overlay_obj.length > 0) {
                            $('#overlay').empty();
                        } 

                        for (var i = 0; i < overlay_obj.length; i++) {
                            var newhtml = '<li id="' + overlay_obj[i]['predef_roi_seed_id'] + '">' + overlay_obj[i]['roi_description'] + '</li>';
                            $('#overlay').append(newhtml);
                        }

                        $('#tabs-min').tabs({active: 2});
                    }
                });
                $('#underlay').selectable({
                    tolerance: 'fit',
                    stop: function(event, ui) {
                        $(event.target).children('.ui-selected').not(':first').removeClass('ui-selected');
                    }
                });
                $("#overlay").sortable({
                    connectWith: ".connectedSortable",
                    dropOnEmpty: true,
                    placeholder: "ui-state-highlight",
                    cancel: ".ui-state-disabled",
                }).disableSelection();
        
                $("#overlay-drop").sortable({
                    connectWith: ".connectedSortable",
                    dropOnEmpty: true,
                    placeholder: "ui-state-highlight",
                    cancel: ".ui-state-disabled",
                    receive:function(event,ui){
                        var id=$(ui.item).attr("id");
                        var all_id= $("#overlay-drop").sortable("toArray");
                        //alert(all_id);
                    
                        for (var i=0;i<overlay_obj.length;i++){
                            if (overlay_obj[i]['predef_roi_seed_id']==id){
                                var html='<tr id="control-'+id+'"><td>'+overlay_obj[i]['roi_description']+'</td>';
                                html+='<td><select class="input-small"><option>Red</option><option>Green</option><option>Blue</option></select></td>';
                                html+='<td><input class="input-small" type="text" value="1.00" id=alpha#'+id+' /></td>';
                                html+='<td><input class="input-small" type="text" value="'+overlay_obj[i]['default_min']+'" id=min#'+id+' /></td>';
                                html+='<td><input class="input-small" type="text" value="'+overlay_obj[i]['default_max']+'" id=max#'+id+' /></td>';
                                html+='<td><button onclick="javascript:;">Visible?</button></td>';
                                $('#overlay-control tr:last').after(html);
                                break;
                            } 
                        }
                        
                        
                    },
                    remove:function(event,ui){
                        var id=$(ui.item).attr("id");
                        $('#control-'+id).remove();
                    },
                }).disableSelection();

            });
            function switchtab() {
                var index = $('#tabs-min a[href="#tabs-1"]').parent().index();
                $('#tabs-min').tabs({active: index});
            }
            
        </script>
    </head>
    <body>
        <div class="row-fluid">
            <div class="navbar navbar-fixed-top">
                <div class="navbar-inner">
                    <div class="container">
                        <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="brand" href="#">Computable Brain</a>
                        <div class="nav-collapse collapse">
                            <div class="nav pull-right">
                                <ul class="nav">
                                    <li class="active"><a href="#">Home</a></li>
                                    <li><a href="#about">About</a></li>
                                    <li><a href="#contact">Sign in</a></li>
                            </div>
                        </div><!--/.nav-collapse -->
                    </div>
                </div>
            </div>
        </div>



        <div class="row-fluid">
            <div class="container">
                <div class="span12">
                    <div id="tabs-min">
                        <ul>
                            <li><a href="#tabs-1">Project</a></li>
                            <li><a href="#tabs-2">POI</a></li>
                            <li><a href="#tabs-3">Overlays</a></li>
                            <li><a href="#tabs-4">Atlas</a></li>
                        </ul>
                        <div id="tabs-1">
                            <div class="selectable-wrapper">
                                <ul class='selectable-single' id='project'>
                                </ul>
                            </div>
                            <div class="selectable-wrapper">
                                <ul class='selectable-single' id='experiment'>
                                </ul>
                            </div>
                            <div class="selectable-wrapper">
                                <ul class='selectable-single' id='underlay'>
                                </ul>
                            </div>
                        </div>
                        <div id="tabs-2">
                            Content to come
                        </div>
                        <div id="tabs-3">  
                            <ul id="overlay" class="connectedSortable">
                                <li class="ui-state-default ui-state-disabled">This Experiment Contains no overlays</li>
                            </ul>
                            <ul id="overlay-drop" class="connectedSortable">
                            </ul>
                            
                            <table id="overlay-control">
                                <tr><th>Overlay Name</th><th>Lookup</th><th>Alpha</th><th>Min</th><th>Max</th></tr>
                            </table>
                        </div>
                        <div id="tabs-4">
                            <div class="selectable-wrapper">
                                <ul class='selectable-single' id='atlaslist'>
                                </ul>
                            </div>
                        </div>
                    </div>


                </div>
                
                <button onclick="switchtab();">Switch to Tab 1</button> 
            </div>
        </div>
        <div class="row-fluid">

        </div>
    </body>
</html>
