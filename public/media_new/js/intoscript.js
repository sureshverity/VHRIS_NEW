var intro; var notyintro;
$(document).ready(function () {
    
});
function defineIntro()
{
    var configArr = [];
    var obj = { intro: application_name + " is a Human Resource Management Software, ideally providing Employee Management, Appraisal, Talent Acquisition, Leave Management, Background Check, Announcements, Analytics and Logs. It enables the administrators to configure the standards used in the organization such as currency codes, date formats, ethnic codes, etc. This application also meets the employee's everyday needs like Leave Management, Service Requests etc. It tracks existing employee data which includes personal history, skills, capabilities and accomplishments.", position: 'right' };
    configArr.push(obj);

    if ($('.tour_dashboard').attr('class')) {
        var obj = { element: ".tour_dashboard", intro: "Dashboard allows you to create Shortcuts and Widgets. You can have all the menus that are used regularly at one place. The easy to access Shortcuts and Widgets present in the dashboard will take you directly to your desired page hence avoiding tiresome menu redirections. You can also display your company announcements and employee's birthdays on the dashboard.", position: 'right' };
        configArr.push(obj);
    }

    if ($('.tour_employeeselfservice').attr('class')) {
        var obj = { element: ".tour_employeeselfservice", intro: "Self-Service enables you to raise and handle leave requests.  You can access your personal information, documents and leave details. You can also check your team details.", position: 'right' };
        configArr.push(obj);
    }

    if ($('.tour_service').attr('class')) {
        var obj = { element: ".tour_service", intro: "Service Request delivers an easy to use IT portal with access to key services and information which are required by the employees. Configure the service request workflow without coding or scripting. You can raise and handle service requests.", position: 'right' };
        configArr.push(obj);
    }

    if ($('.tour_humanresource').attr('class')) {
        var obj = { element: ".tour_humanresource", intro: "HR (Human Resource) deals with the user, leave and holiday management configuration. It stores the employee data which includes personal history, skills, documents, education, visa & immigration etc.", position: 'right' };
        configArr.push(obj);
    }

    if ($('.tour_performanceappraisal').attr('class')) {
        var obj = { element: ".tour_performanceappraisal", intro: "Appraisals let you configure appraisal settings like parameters, skills, ratings and questions. You can provide a self-appraisal and appraise your employees. Users can also give a feedback about their manager using the Feed Forward feature.", position: 'right' };
        configArr.push(obj);
    }


    if ($('.tour_requisition').attr('class')) {
        var obj = { element: ".tour_requisition", intro: "Talent Acquisition helps you to monitor and maintain processes like initializing a requisition, managing CVs, scheduling interviews, shortlisting and selecting a candidate.", position: 'right' };
        configArr.push(obj);
    }

    if ($('.tour_backgroundchecks').attr('class')) {
        var obj = { element: ".tour_backgroundchecks", intro: "Background Check module enables the pre and post-employment screening process. You can configure the screening types and manage the agencies you wish to work with.", position: 'right' };
        configArr.push(obj);
    }

    if ($('.tour_organization').attr('class')) {
        var obj = { element: ".tour_organization", intro: "Manage your organization's details, announcements, business units, departments and organization hierarchy here.", position: 'right' };
        configArr.push(obj);
    }

    if ($('.tour_reports').attr('class')) {
        var obj = { element: ".tour_reports", intro: "Analytics uses descriptive techniques to represent your organization's data and allows you to generate custom reports and then export them to Excel or PDF.", position: 'right' };
        configArr.push(obj);
    }
    if ($('.tour_siteconfiguration').attr('class')) {
        var obj = { element: ".tour_siteconfiguration", intro: "Site Configuration allows you to configure the standards used in your organization such as locations, currency codes, date formats, ethnic codes, etc.", position: 'right' };
        configArr.push(obj);
    }
    if ($('.tour_managemodules').attr('class')) {
        var obj = { element: ".tour_managemodules", intro: "Manage your " + application_name + " system by choosing the modules required for your organization. You can enable or disable the modules at any given time.", position: 'right' };
        configArr.push(obj);
    }
    if ($('.tour_timemanagement').attr('class')) {
        var obj = { element: ".tour_timemanagement", intro: "Time consists of timesheet management, tracking employee's activities & time offs. Generate accurate time reports on the basis of employees, projects and time period. Configure your organization’s projects and clients.", position: 'right' };
        configArr.push(obj);
    }
    if ($('.tour_logs').attr('class')) {
        var obj = { element: ".tour_logs", intro: "Logs allow you to check the amount of activity happening on the application along with the daily users' login record.", position: 'right' };
        configArr.push(obj);
    }
    intro = introJs();
    intro.setOptions({ steps: configArr });

}



function StartTour() {
    var url_loc = window.location.href;
    if (url_loc.indexOf('welcome') < 0) {
        window.location.href = base_url + "/welcome#starttour";
    }
    else {
        if (intro != null)
        {
            intro.exit();
            intro = null;
            defineIntro();
        }
        else
        {
            defineIntro();
        }
        intro.start().onchange(function (targetElement) {
            if (targetElement != undefined) {
                if ($(targetElement).hasClass("tour_dashboard") || $(targetElement).hasClass("tour_employeeselfservice") || $(targetElement).hasClass("tour_service")) {
                    $(".page-sidebar").mCustomScrollbar("scrollTo", 0);

                }
                else {

                    var sclassArry = $(targetElement).attr("class").match(/tour[\w_]*\b/);
                    var sclass = "";
                    if (sclassArry != null)
                    {
                        sclass = sclassArry[0];
                    }
                    switch (sclass) {
                        case "tour_humanresource":
                            $(".page-sidebar").mCustomScrollbar("scrollTo", 50);
                            setTimeout(function () {
                                $('.introjs-tooltipReferenceLayer').css({ top: '387px' });
                                $('.introjs-helperLayer ').css({ top: '387px' });
                            }, 200)
                            break;
                        case "tour_performanceappraisal":
                            $(".page-sidebar").mCustomScrollbar("scrollTo", 158);
                            setTimeout(function () {
                                $('.introjs-tooltipReferenceLayer').css({ top: '387px' });
                                $('.introjs-helperLayer ').css({ top: '387px' });
                            }, 200)
                            break;
                        case "tour_requisition":
                            $(".page-sidebar").mCustomScrollbar("scrollTo", 266);
                            setTimeout(function () {
                                $('.introjs-tooltipReferenceLayer').css({ top: '387px' });
                                $('.introjs-helperLayer ').css({ top: '387px' });
                            }, 200)
                            break;
                        case "tour_backgroundchecks":
                            $(".page-sidebar").mCustomScrollbar("scrollTo", 320);
                            setTimeout(function () {
                                $('.introjs-tooltipReferenceLayer').css({ top: '387px' });
                                $('.introjs-helperLayer ').css({ top: '387px' });
                            }, 200)
                            break;
                        case "tour_organization":
                            $(".page-sidebar").mCustomScrollbar("scrollTo", 374);
                            setTimeout(function () {
                                $('.introjs-tooltipReferenceLayer').css({ top: '387px' });
                                $('.introjs-helperLayer ').css({ top: '387px' });
                            }, 200)
                            break;
                        case "tour_reports":
                            $(".page-sidebar").mCustomScrollbar("scrollTo", 428);
                            setTimeout(function () {
                                $('.introjs-tooltipReferenceLayer').css({ top: '387px' });
                                $('.introjs-helperLayer ').css({ top: '387px' });
                            }, 200)
                            break;
                        case "tour_siteconfiguration":
                            $(".page-sidebar").mCustomScrollbar("scrollTo", 482);
                            setTimeout(function () {
                                $('.introjs-tooltipReferenceLayer').css({ top: '387px' });
                                $('.introjs-helperLayer ').css({ top: '387px' });
                            }, 200)
                            break;
                        case "tour_managemodules":
                            $(".page-sidebar").mCustomScrollbar("scrollTo", 536);
                            setTimeout(function () {
                                $('.introjs-tooltipReferenceLayer').css({ top: '387px' });
                                $('.introjs-helperLayer ').css({ top: '387px' });
                            }, 200)
                            break;
                        case "tour_timemanagement":
                            $(".page-sidebar").mCustomScrollbar("scrollTo", 570);
                            setTimeout(function () {
                                $('.introjs-tooltipReferenceLayer').css({ top: '517px' });
                                $('.introjs-helperLayer ').css({ top: '517px' });
                            }, 200)
                            break;
                        case "tour_logs":
                            $(".page-sidebar").mCustomScrollbar("scrollTo", 570);
                            setTimeout(function () {
                                $('.introjs-tooltipReferenceLayer').css({ top: '571px' });
                                $('.introjs-helperLayer ').css({ top: '571px' });
                            }, 200)
                            break;
                        default:
                            
                            break;
                    }
                }

            }
        }).onexit(function () {
            notyintro.close();
            window.location.href = base_url + "/welcome";
        }).oncomplete(function () {
            notyintro.close();
            window.location.href = base_url + "/welcome";
        });
    }
}
function notyConfirm() {
    notyintro = noty({
        text: "<div class='panel panel-noclass'><div class='panel-heading'><h3 class='panel-title'>Start Tour</h3><ul class='panel-controls'><li><a href='#' class='pnl-close'><span class='fa fa-times'></span></a></li></ul></div><div class='panel-body'><h3 style='color:#fff'>Welcome to VHRIS !!</h3><h5 class='text-success'>Here for the first time? </h5><p class='text-warning'>For the best VHRIS tour experience, please maximize your browser.</p><br/><button class='btn btn-info pull-right start-tour'><span class='fa fa-arrow-circle-o-right '></span>Start the Tour.</button></div><div class='panel-footer'><button class='btn btn-default pull-right endtour'>End the Tour</button><button class='btn btn-default restart' >Restart the Tour</button></div></div>",
        layout: 'bottomRight',
        buttons: [],
        callback: {
            afterShow: function () {
                $(".start-tour").on("click", function () {
                    StartTour();
                });
                $(".restart").on("click", function () {
                    restartTour();
                });
                $(".endtour").on("click", function () {
                    endTour();
                });
            }
        }
    })
}
function endTour()
{
    notyintro.close();
    intro.exit()
}
function restartTour()
{
    intro.goToStep(1);
}

$(window).load(function () {
    
    var url_loc = window.location.href;
    var tourstring = url_loc.split('welcome#')[1];
    if (tourstring == "starttour")
    {
        notyConfirm();
        StartTour();
    }
});