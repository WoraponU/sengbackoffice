$(document).ready(function(){$(".modal").modal(),$("select").material_select(),$(".datepicker").pickadate({selectMonths:!0,selectYears:15}),$("#errorMessage").length&&Materialize.toast(toastContent,5e3,"red")});