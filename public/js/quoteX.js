$(document).ready(function () {


    $(document).ready(function () {
        // Array of remark selectors
        const remarkSelectors = [
            { remark: '#rsa_remark', label: '#rsa_rem_lbl' },
            { remark: '#shield_remark', label: '#shield_rem_lbl' },
            { remark: '#ins_remark', label: '#ins_rem_lbl' },
            { remark: '#rto_remark', label: '#rto_rem_lbl' },
            { remark: '#apack_remark', label: '#apack_rem_lbl' },
            { remark: '#extra_disc_remark', label: '#extra_disc_rem_lbl' },
            { remark: '#spl_disc_inv_remark', label: '#spl_disc_inv_rem_lbl' },
            { remark: '#cu_ins', label: '#cu_ins' },
            { remark: '#rto_dis', label: '#rto_dis' },
            { remark: '#rto_charges_remark', label: '#rto_charges_rem_lbl' }
        ];

        // Hide remarks and set them as not required
        remarkSelectors.forEach(selector => {
            $(selector.remark).hide();
            $(selector.label).hide();
            $(selector.remark).prop("required", false);
        });

        // Initialize rto_dis_amt
        $('#rto_dis_amt').prop("required", false).val(0);

        // Set value for st_apack
        const c = parseInt($("#cu_apack").val()) || 0; // Adding fallback to avoid NaN
        $("#st_apack").val(c);

        // Initialize select2 for c_apack
        $('#c_apack').select2({
            placeholder: "Select Accessories"
        });
    });




    updateValues();

    ///Set VId on color Change

    $('#vh_color').on('change', function () {
        var vhColorVal = $("#vh_color").val(); // Store the value once
        var cval = parseInt(vhColorVal);

        // Check if the parsed value is a valid number
        if (!isNaN(cval)) {
            $("#vid").val(cval);
        } else {
            console.error("Invalid value for vehicle color: " + vhColorVal);
            alert("Please select a valid vehicle color.");
        }
    });


    ///Set 


    /////////////////////////////
    //    Enquiry autocomplete
    ///////////////////////////
    $('#cu_extra_disc').on('change', function () {
        const inputVal = $("#cu_extra_disc").val();
        let cval = parseInt(inputVal);

        // Check if the input value is a valid number
        if (isNaN(cval)) {
            console.error("Invalid value for extra discount: " + inputVal);
            alert("Please enter a valid number for the extra discount.");
            $("#cu_extra_disc").val(''); // Reset the field on error
            $("#extra_disc_cust").html(0); // Reset customer discount
            $('#extra_disc_remark').hide();
            $('#extra_disc_rem_lbl').hide();
            $('#extra_disc_remark').prop("required", false);
            return; // Exit if the value is invalid
        }

        $("#extra_disc_cust").html(cval);

        // Show or hide the remark based on the value
        if (cval > 0) {
            $('#extra_disc_remark').show();
            $('#extra_disc_rem_lbl').show();
            $('#extra_disc_remark').prop("required", true);
        } else {
            $('#extra_disc_remark').hide();
            $('#extra_disc_rem_lbl').hide();
            $('#extra_disc_remark').prop("required", false);
        }

        updateValues();
    });


    $('#cu_rto_ch').on('change', function () {
        var cval = parseInt($("#cu_rto_ch").val());
        var dval = parseInt($("#rto_ch_st").html());

        if (!isNaN(cval)) {
            if (cval >= dval) {
                $('#rto_charges_remark, #rto_charges_rem_lbl').hide();
                $('#rto_charges_remark').prop("required", false);
                $("#cu_rto_ch").val(dval); // Set cval to dval if it's greater
            } else {
                $('#rto_charges_remark, #rto_charges_rem_lbl').show();
                $('#rto_charges_remark').prop("required", true);
            }
        } else {
            console.error("Invalid value for RTO charges.");
        }
    });

    $('#spl_disc').on('change', function () {
        var cval = parseInt($("#spl_disc").val());

        if (!isNaN(cval)) {
            $("#spl_disc_cust").html(cval);
            if (cval > 0) {
                $('#spl_disc_remark, #spl_disc_rem_lbl').show();
                $('#spl_disc_remark').prop("required", true);
            } else {
                $('#spl_disc_remark, #spl_disc_rem_lbl').hide();
                $('#spl_disc_remark').prop("required", false);
            }
        } else {
            console.error("Invalid value for special discount.");
        }
        updateValues();
    });

    $('#cash').on('change', function () {
        var cashL = parseInt($("#cash_only").val()) || 0;
        var creditL = parseInt($("#credit_only").val()) || 0;
        var fixed = cashL + creditL;
        var totalL = parseInt($("#total_bif").val()) || 0;
        var ttla = parseInt($("#cu_ttl_add").val()) || 0;
        var credit = parseInt($("#credit").val()) || 0;
        var cash = parseInt($("#cash").val()) || 0;
        var ex = parseInt($("#st_ex").val()) || 0;

        // Calculate cash and credit values
        if (cash > fixed) {
            cash = totalL - fixed;
            credit = 0;
        } else if (cash <= 0) {
            cash = 0;
            credit = totalL - fixed;
        } else {
            credit = totalL - fixed - cash;
        }

        var tcash = cash + cashL;
        var tcredit = credit + creditL;
        $("#credit").val(credit);
        $("#cash").val(cash);

        var tinv = ex - tcash;
        var tcs_limit = parseInt($("#tcs_limit").html()) || 0;
        var tcs = 0;

        if (tinv >= tcs_limit) {
            var tcs_rate = parseInt($("#tcs_rate").html()) || 0;
            tcs = (tinv * tcs_rate) / 100;
        }

        var invoice = tinv + tcs;
        var onroad = ex + tcs + ttla - totalL;

        $("#cu_onroad").html(onroad);
        $("#cu_invoice").html(invoice);
        $("#cu_tcs").html(tcs);
        $("#cust_tcs").val(tcs);
        $("#cust_invoice").val(invoice);
        $("#cust_onroad").val(onroad);
    });


    $('#c_corp_disc').on('change', function () {
        var cval = parseInt($("#c_corp_disc option:selected").val());
        var ctxt = $("#c_corp_disc option:selected").text();
        $("#cu_corp_disc_details").val(ctxt);

        if (cval > 0) {
            var ms = parseInt($("#c_corp_disc option:selected").data('ms'));
            if (ms === 1) {
                // Fetch discount status data
                var scrap = parseInt($("#c_corp_disc option:selected").data('scrap')) || 0;
                var exch = parseInt($("#c_corp_disc option:selected").data('exch')) || 0;
                var loyl = parseInt($("#c_corp_disc option:selected").data('loyl')) || 0;

                // Reset values
                $("#cu_enl_disc").val(0);
                $("#enl_disc_cust").html(0);
                $('#c_enl_disc').children('option[data-type=EXCHANGE], option[data-type=LOYALTY], option[data-type=SCRAPPAGE]').attr('disabled', true);

                // Enable or disable based on conditions
                if (exch > 0) $('#c_enl_disc').children('option[data-type=EXCHANGE]').attr('disabled', false);
                if (loyl > 0) $('#c_enl_disc').children('option[data-type=LOYALTY]').attr('disabled', false);
                if (scrap > 0) $('#c_enl_disc').children('option[data-type=SCRAPPAGE]').attr('disabled', false);

                $('#c_enl_disc').val(0).trigger('change');
            } else {
                // Enable all discount options if ms is not 1
                $('#c_enl_disc').children('option[data-type=SCRAPPAGE], option[data-type=EXCHANGE], option[data-type=LOYALTY]').attr('disabled', false);
                $("#cu_enl_disc").val(0);
                $("#enl_disc_cust").html(0);
                $('#c_enl_disc').val(0).trigger('change');
            }

            $("#corp_disc_cust").html(cval);
            $("#cu_corp_disc").val(cval);
            updateValues();
        }
    });


    // $('#c_master_disc').on('change', function() {
    //     var cval = parseInt($("#c_master_disc option:selected").val());
    //     if (cval > 0) {
    //         var corp = $("#c_master_disc option:selected").data('corp');
    //         var exch = $("#c_master_disc option:selected").data('exch');
    //         var loyl = $("#c_master_disc option:selected").data('loyl');
    //         if (exch == 0 || loyl == 0) {
    //             //alert("Please select Exchange or Loyalty Bonus");
    //             $("#cu_enl_disc").val(0);
    //             $("#enl_disc_cust").html(0);
    //             //$("#exch_row").hide();
    //             $('#c_enl_disc').children('option[data-type=EXCHANGE]').
    //             attr('disabled', true);
    //         } else {
    //             $("#exch_row").show();
    //         }
    //         if (corp == 0) {
    //             $("#cu_corp_disc").val(0);
    //             $("#corp_disc_cust").html(0);
    //             $("#corp_row").hide();
    //         } else {
    //             $("#corp_row").show();
    //         }
    //     } else {
    //         $("#corp_row").show();
    //         $("#exch_row").show();
    //     }
    //     $("#master_disc_cust").html(cval);
    //     $("#cu_master_disc").val(cval);
    //     updateValues();$(document).ready(function () {
    // Array of remark selectors
    const remarkSelectors = [
        { remark: '#rsa_remark', label: '#rsa_rem_lbl' },
        { remark: '#shield_remark', label: '#shield_rem_lbl' },
        { remark: '#ins_remark', label: '#ins_rem_lbl' },
        { remark: '#rto_remark', label: '#rto_rem_lbl' }
    ];

    // Hide remarks and set them as not required
    remarkSelectors.forEach(({ remark, label }) => {
        $(remark).hide().prop("required", false);
        $(label).hide();
    });

    // Initialize rto_dis_amt
    $('#rto_dis_amt').prop("required", false).val(0);
    $("#st_apack").val(parseInt($("#cu_apack").val()) || 0);
    $('#c_apack').select2({ placeholder: "Select Accessories" });

    // Update values function
    function updateValues() {
        // Logic related to updating values
    }

    function setValue(selector, val) {
        $(selector).val(val);
    }

    function handleNumericInput(selector, defaultValue = 0) {
        return parseInt($(selector).val()) || defaultValue;
    }

    $('#vh_color').on('change', function () {
        const vhColorVal = $(this).val();
        const cval = handleNumericInput(this);

        if (!isNaN(cval)) {
            $("#vid").val(cval);
        } else {
            console.error("Invalid value for vehicle color: " + vhColorVal);
            alert("Please select a valid vehicle color.");
        }
    });

    /////////////////////////////
    //    Enquiry autocomplete
    ///////////////////////////
    $('#cu_extra_disc').on('change', function () {
        const inputVal = $(this).val();
        const cval = handleNumericInput(this);

        if (isNaN(cval)) {
            console.error("Invalid value for extra discount: " + inputVal);
            alert("Please enter a valid number for the extra discount.");
            $(this).val(''); // Reset the field on error
            $("#extra_disc_cust").html(0); // Reset customer discount
            remarkSelectors[0].remark.hide().prop("required", false);
            remarkSelectors[0].label.hide();
        } else {
            $("#extra_disc_cust").html(cval);
            const shouldShowRemark = cval > 0;
            $(remarkSelectors[0].remark).toggle(shouldShowRemark).prop("required", shouldShowRemark);
            updateValues();
        }
    });

    $('#cu_rto_ch').on('change', function () {
        const cval = handleNumericInput(this);
        const dval = handleNumericInput("#rto_ch_st");

        if (!isNaN(cval)) {
            const shouldShowRemark = cval < dval;
            $(remarkSelectors[1].remark).toggle(shouldShowRemark).prop("required", shouldShowRemark);
            if (cval >= dval) setValue("#cu_rto_ch", dval);
        } else {
            console.error("Invalid value for RTO charges.");
        }
    });

    function handleDiscountChange(discSelector, discCustSelector, remarkSelect) {
        $(discSelector).on('change', function () {
            const cval = handleNumericInput(this);

            if (!isNaN(cval)) {
                $(discCustSelector).html(cval);
                const shouldShowRemark = cval > 0;
                $(remarkSelect).toggle(shouldShowRemark).prop("required", shouldShowRemark);
            } else {
                console.error("Invalid value for discount.");
            }
            updateValues();
        });
    }

    handleDiscountChange('#spl_disc', '#spl_disc_cust', '#spl_disc_remark');
    handleDiscountChange('#c_corp_disc', '#corp_disc_cust', '#c_enl_disc');

    $('#cash, #cu_rto_ch').on('change', function () {
        const cashL = handleNumericInput("#cash_only");
        const creditL = handleNumericInput("#credit_only");
        const totalL = handleNumericInput("#total_bif");
        const ttla = handleNumericInput("#cu_ttl_add");
        const credit = handleNumericInput("#credit");
        const cash = handleNumericInput("#cash");
        const ex = handleNumericInput("#st_ex");

        if (cash > cashL + creditL) {
            credit = 0;
            cash = totalL - (cashL + creditL);
        } else if (cash <= 0) {
            cash = 0;
            credit = totalL - (cashL + creditL);
        } else {
            credit = totalL - (cashL + creditL) - cash;
        }

        setValue("#credit", credit);
        setValue("#cash", cash);

        const tinv = ex - (cash + cashL);
        const tcs_limit = handleNumericInput("#tcs_limit");
        let tcs = (tinv >= tcs_limit) ? (tinv * handleNumericInput("#tcs_rate")) / 100 : 0;
        const invoice = tinv + tcs;
        const onroad = ex + tcs + ttla - totalL;

        $("#cu_onroad").html(onroad);
        $("#cu_invoice").html(invoice);
        $("#cu_tcs").html(tcs);
        $("#cust_tcs").val(tcs);
        $("#cust_invoice").val(invoice);
        $("#cust_onroad").val(onroad);
    });

    // Function to handle insurance dis/enabling
    function handleInsuranceClick(buttonId, isReadonly) {
        $(buttonId).click(function () {
            const cval = handleNumericInput("#ins_cust");
            setValue("#cu_ins", cval);
            $('#ins_remark').prop("required", !isReadonly).toggle(!isReadonly).val(0);
            $('#ins_rem_lbl').toggle(!isReadonly);
        });
    }

    handleInsuranceClick('#insvdup', false);
    handleInsuranceClick('#insvorg', true);

    // Pincode autocomplete
    $('#pincode').on('change', function () {
        const pin = handleNumericInput(this);
        if (pin > 110000 && pin < 999999) {
            $.get("{{ url('getLocData') }}/" + pin, function (data) {
                if (data && data.success) {
                    data.data.forEach(value => {
                        switch (value.level) {
                            case "STATE":
                                $('#state').val(value.name);
                                break;
                            case "DISTRICT":
                                $('#district').val(value.name);
                                break;
                            case "TEHSIL":
                                $('#tehsil').val(value.name === "0" || value.name === "NA" ? $('#district').val() : value.name);
                                break;
                            default:
                                handlePostOfficeData(value);
                        }
                    });
                } else {
                    resetLocationFields();
                }
            });
        } else {
            alert("Please enter a valid pincode");
            resetLocationFields();
        }
    });

    function resetLocationFields() {
        $('#postoffice').html('').prop("disabled", true);
        $('#tehsil, #district, #state').val("");
    }

    function handlePostOfficeData(value) {
        $('#postoffice').html('');
        let newopt = new Option("Please select PO", 0, true, true);
        $("#postoffice").append(newopt).trigger('change');
        if (value.posts) {
            value.posts.forEach(value1 => {
                let newopt = new Option(value1.name, value1.id);
                $("#postoffice").append(newopt).trigger('change');
            });
        }
        $("#postoffice").prop("disabled", false);
    }

    // Customer autocomplete
    $('#mobile').on('change', function () {
        const mob = handleNumericInput(this, NaN);
        if (!isNaN(mob)) {
            $.get("{{ url('/quotes/getCustomer') }}/" + mob, function (data) {
                if (data) {
                    $("#name, #email, #mobile, #address, #pincode").val(data.name, data.email, data.mobile, data.address, data.pincode);
                    $("#pincode").trigger("change");
                }
            });
            $("#quote_personal").show();
        } else {
            alert("Please enter a valid 10 digits Mobile Number");
            $("#quote_personal").hide();
        }
    });

    // RSA Customization
    function handleCustomization(selector, defaultValueSelector) {
        $(selector).change(function () {
            const cval = handleNumericInput(this);
            const dval = handleNumericInput(defaultValueSelector);

            $('#st_rsa').val(cval).html(cval);
            $('#cu_rsa').val(cval).html(cval);
            $('#rsa_remark').prop("required", cval === 0).toggle(cval === 0);
            updateValues();
        });
    }

    handleCustomization("#c_rsa", "#default_rsa");
    handleCustomization("#c_shield", "#default_shield");
    handleCustomization("#c_ins", "#default_ins");
    handleCustomization("#c_rto", "#default_rto");

    // Accessories Customization
    $('#c_apack').on('select2:select select2:unselect', function (e) {
        const price = $(e.params.data.element).data('price');
        let cval = handleNumericInput("#cu_apack");
        const dval = handleNumericInput("#apack_general");
        const mval = handleNumericInput("#min_apack");

        cval += e.type === 'select2:select' ? price : -price;
        $("#apack_cust").html(cval).val(cval);
        $("#cu_apack").val(cval);
        $("#apack_st").html(cval > dval ? cval : dval).val(cval > dval ? cval : dval);

        if (cval < mval) {
            $('#cu_apack_disc, #apack_disc_cust, #c_apack_disc').val(0).html(0);
        } else {
            $('#cu_apack_disc').val(handleNumericInput("#apack_general_disc"));
            $('#apack_disc_cust').html(handleNumericInput("#apack_general_disc"));
            $('#c_apack_disc').html(handleNumericInput("#apack_general_disc"));
        }

        updateValues();
    });


    // });

    $('#c_enl_disc').on('change', function () {
        var cval = parseInt($("#c_enl_disc option:selected").val());
        var type = $("#c_enl_disc option:selected").data('type');
        var ctxt = $("#c_enl_disc option:selected").text();
        $("#cu_enl_type").val(type);
        $("#cu_enl_details").val(ctxt);
        //alert("Enl Bonus Selected : " + cval + ", Type : " + type + ", Text : " + ctxt);
        // alert("Type : " + type + ", Amount : " + cval);
        if (type == "SCRAPPAGE") {
            // alert("SCRAPPAGE Bonus Selected");
            $('#rto_dis').show();
            $('#rto_dis_amt').prop("required", true);
            $('#rto_dis_amt').prop("disabled", false);
            $('#rto_dis_amt').val(0);
            $('#rto_dis_amt').focus();
        } else {
            $('#rto_dis').hide();
            $('#rto_dis_amt').prop("required", false);
            $('#rto_dis_amt').prop("disabled", true);
            $('#rto_dis_amt').val(0);
        }
        $("#enl_disc_cust").html(cval);
        $("#cu_enl_disc").val(cval);
        //     $('#rto_disc').hide();
        // $('#rto_disc_amt').prop("required", false);
        // $('#rto_disc_amt').val(0);
        updateValues();
    });

    $('#rto_dis_amt').on('change', function () {
        var orto = $('#cu_rto').val();
        var rdis = $('#rto_dis_amt').val();
        var nrto = orto - rdis;
        $('#rto_net_amt').val(nrto);
        //updateValues();
    });






    $('#enq_id').on('change', function () {
        if (!isNaN(parseInt($("#enq_id").val()))) {
            var enqid = parseInt($("#enq_id").val());
            $("#st_enqid").val(enqid);
            //if(data.fid != data.cid)
            //{
            //alert("This enquiry is registerd with other FSC. You cant create a quote on it")
            if (true) {
                $.get("{{ url('/quotes/enqdata') }}/" + enqid, function (data) {
                    if (data.eid) {
                        var vid = parseInt($("#vid").val());
                        if (data.vid != vid) {
                            alert(
                                "This enquiry number is for other Vehicle cant create a Quote for this vehicle"
                            );
                            $("#quote_personal").hide();
                        } else {
                            if (data.qid) {
                                alert("A " + data.status + " quote #" + data.qid +
                                    "  already exist for this Enquiry Number. Cant Create a new quote on this."
                                );
                                $("#quote_personal").hide();
                            } else {
                                //var plid = parseInt($("#plid").val());
                                //if(data.plid != plid)
                                //{
                                //	alert("This enquiry number is for other permit type. Please select vehicle from correct permit");
                                //	$("#quote_personal").hide();
                                //}
                                var pid = data.pid;
                                $.get("{{ url('/quotes/person') }}/" + pid, function (
                                    pdata) {
                                    if (pdata) {
                                        $("#name").val(pdata.name);
                                        $("#email").val(pdata.email);
                                        $("#mobile").val(pdata.mobile);
                                        $("#address").val(pdata.address);
                                        $("#pincode").val(pdata.pincode);
                                        $("#pincode").trigger("change");
                                    }
                                });
                                $("#quote_personal").show();
                            }
                        }

                    } else {
                        $("#quote_personal").show();
                    }
                });
            } else {
                alert("Please enter a valid Enquiry Number");
                $("#quote_personal").hide();
            }
        } else {
            alert("Please enter a valid Enquiry Number");
            $("#quote_personal").hide();
        }
    });

    //////////////////////////
    // Insurance Other amount
    /////////////////////////
    $('#insvdup').click(function () {
        var cval = parseInt($("#ins_cust").html());
        $('#cu_ins').prop("readonly", false);
        $("#cu_ins").val(cval);
        $('#ins_remark').prop("required", true);
        $('#ins_remark').show();
        $('#ins_rem_lbl').show();
    });


    $('#insvorg').click(function () {
        var cval = parseInt($("#ins_cust").html());
        $('#cu_ins').prop("readonly", true);
        $("#cu_ins").val(cval);
        $('#ins_remark').prop("required", false);
        $('#ins_remark').hide();
        $('#ins_rem_lbl').hide();
    });



    /////////////////////////////
    //      Pincode autocomplete
    ///////////////////////////////

    $('#pincode').on('change', function () {
        if (!isNaN($("#pincode").val())) {
            var pin = parseInt($("#pincode").val());
            if (pin > 110000 && pin < 999999) {
                $.get("{{ url('getLocData') }}/" + pin, function (data) {
                    if (data) {
                        if (data.success) {
                            $.each(data.data, function (index, value) {
                                if (value.level == "STATE")
                                    $('#state').val(value.name);
                                else if (value.level == "DISTRICT")
                                    $('#district').val(value.name);
                                else if (value.level == "TEHSIL") {
                                    if (value.name == "0" || value.name == "NA")
                                        $('#tehsil').val($('#district').val());
                                    else
                                        $('#tehsil').val(value.name);
                                } else {
                                    $('#postoffice').html('');
                                    var newopt = new Option("Please select PO",
                                        0,
                                        true, true);
                                    //newopt.prop("selected",true);
                                    //newopt.prop("disabled",true);
                                    $("#postoffice").append(newopt).trigger(
                                        'change');
                                    $.each(value, function (index, value1) {
                                        var newopt = new Option(value1
                                            .name,
                                            value1.id, true, true);
                                        // Append it to the select
                                        $("#postoffice").append(newopt)
                                            .trigger('change');
                                    });
                                    $("#postoffice").prop("disabled", false);
                                }
                            });
                        } else {
                            $('#postoffice').html('');
                            $('#tehsil').val("");
                            $('#district').val("");
                            $('#state').val("");
                            $("#postoffice").prop("disabled", true);
                        }
                    } else {
                        $('#postoffice').html('');
                        $('#tehsil').val("");
                        $('#district').val("");
                        $('#state').val("");
                        $("#postoffice").prop("disabled", true);
                    }
                });
            } else {
                $('#postoffice').html('');
                $('#tehsil').val("");
                $('#district').val("");
                $('#state').val("");
                $("#postoffice").prop("disabled", true);
                alert("Please enter valid pincode");
            }
        } else {
            $('#postoffice').html('');
            $('#tehsil').val("");
            $('#district').val("");
            $('#state').val("");
            $("#postoffice").prop("disabled", true);
            alert("Please enter valid pincode");
        }
    });

    //////////////////////////
    // Customer autocomplete
    /////////////////////////


    $('#mobile').on('change', function () {
        if (!isNaN(parseInt($("#mobile").val()))) {
            var mob = parseInt($("#mobile").val());
            //if(data.fid != data.cid)
            //{
            //alert("This enquiry is registerd with other FSC. You cant create a quote on it")
            if (true) {
                $.get("{{ url('/quotes/getCustomer') }}/" + mob, function (data) {
                    if (data) {
                        $("#name").val(data.name);
                        $("#email").val(data.email);
                        $("#mobile").val(data.mobile);
                        $("#address").val(data.address);
                        $("#pincode").val(data.pincode);
                        $("#pincode").trigger("change");
                    }
                });
                $("#quote_personal").show();
            } else {
                alert("Please enter a valid 10 digits Mobile Number");
                $("#quote_personal").hide();
            }
        } else {
            alert("Please enter a valid 10 digits Mobile Number");
            $("#quote_personal").hide();
        }
    });


    ////////////////////////
    // RSA Customization
    /////////////////////
    $("#c_rsa").change(function () {
        var cval = parseInt($("#c_rsa option:selected").val());
        var ctxt = $("#c_rsa option:selected").text();
        $("#cu_rsa_details").val(ctxt);
        var dval = parseInt($("#default_rsa").val());
        //alert(cval);

        if (cval == 0) {
            $('#rsa_remark').prop("required", true);
            $('#rsa_remark').show();
            $('#rsa_rem_lbl').show();
            $('#rsa_st').html(dval);
            $('#st_rsa').val(dval);
            $('#rsa_cust').html(cval);
            $('#cu_rsa').val(cval);

            $('#cu_rsa_disc').val(0);
            $('#rsa_disc_cust').html(0);
            $('#c_rsa_disc').html(0);


        } else {
            $('#rsa_st').html(cval);
            $('#st_rsa').val(cval);
            $('#cu_rsa').val(cval);
            $('#rsa_cust').html(cval);

            $('#rsa_remark').prop("required", false);
            $('#rsa_remark').hide();
            $('#rsa_rem_lbl').hide();
            var dd = parseInt($("#default_rsa_disc").val());
            $('#cu_rsa_disc').val(dd);
            $('#rsa_disc_cust').html(dd);
            $('#c_rsa_disc').html(dd);
        }
        updateValues();
    });

    ////////////////////////
    // Shield Customization
    /////////////////////
    $("#c_shield").change(function () {
        var cval = parseInt($("#c_shield option:selected").val());
        var ctxt = $("#c_shield option:selected").text();
        $("#cu_shield_details").val(ctxt);
        var dval = parseInt($("#default_shield").val());
        //alert(cval);

        if (cval == 0) {
            $('#shield_st').html(dval);
            $('#st_shield').val(dval);
            $('#cu_shield').val(cval);
            $('#shield_cust').html(cval);
            $('#shield_remark').prop("required", true);
            $('#shield_remark').show();
            $('#shield_rem_lbl').show();
            $('#cu_shield_disc').val(0);
            $('#shield_disc_cust').html(0);
            $('#c_shield_disc').html(0);
        } else {
            $('#shield_st').html(cval);
            $('#st_shield').val(cval);
            $('#cu_shield').val(cval);
            $('#shield_cust').html(cval);
            $('#shield_remark').prop("required", false);
            $('#shield_remark').hide();
            $('#shield_rem_lbl').hide();
            var dd = parseInt($("#default_shield_disc").val());
            $('#cu_shield_disc').val(dd);
            $('#shield_disc_cust').html(dd);
            $('#c_shield_disc').html(dd);
        }
        updateValues();
    });

    ////////////////////////
    // Insurance Customization
    /////////////////////
    $("#c_ins").change(function () {
        var cval = parseInt($("#c_ins option:selected").val());
        var ctxt = $("#c_ins option:selected").text();
        $("#cu_ins_details").val(ctxt);
        var dval = parseInt($("#default_ins").val());
        //alert(cval);

        if (cval == 0) {
            $('#ins_st').html(0);
            $('#st_ins').val(0);
            $('#cu_ins').val(0);
            $('#ins_cust').html(0);
            $('#ins_remark').prop("required", true);
            $('#ins_remark').show();
        } else {
            $('#ins_st').html(cval);
            $('#st_ins').val(cval);
            $('#cu_ins').val(cval);
            $('#ins_cust').html(cval);
            $('#ins_remark').prop("required", false);
            $('#ins_remark').hide();
        }
        updateValues();
    });

    ////////////////////////
    // RTO Customization
    /////////////////////
    $("#c_rto").change(function () {
        var cval = parseInt($("#c_rto option:selected").val());
        var ctxt = $("#c_rto option:selected").text();
        $("#cu_rto_details").val(ctxt);
        var dval = parseInt($("#default_rto").val());
        //alert(cval);

        if (cval < 2) {
            $('#rto_st').html(0);
            $('#st_rto').val(0);
            $('#cu_rto').val(0);
            $('#rto_cust').html(0);
            $('#rto_remark').prop("required", true);
            $('#rto_remark').show();
        } else {
            $('#rto_st').html(cval);
            $('#st_rto').val(cval);
            $('#cu_rto').val(cval);
            $('#rto_cust').html(cval);
            $('#rto_remark').prop("required", false);
            $('#rto_remark').hide();
        }
        updateValues();
    });


    //////////////////////////////
    // Accessories Customization
    //////////////////////////////



    $('#c_apack').on('select2:select', function (e) {
        let price = $(e.params.data.element).data('price');
        let aval = $(e.params.data.element).val();
        let atxt = $(e.params.data.element).text();
        var aitm = atxt.split("@");
        // alert("Accessories Selected : " + aval + ", Price : " + price + ", Text : " + aitm[0]
        //.trim());
        var prmt = aval + "||" + price + "||" + aitm[0].trim();
        var tmp = $("#cu_apack_details").val();
        var apar = tmp.split("###");
        apar.push(prmt);
        $("#cu_apack_details").val(apar.join("###"));
        //alert($(                        "#cu_apack_details").val());
        //alert(price);
        var cval = parseInt($("#cu_apack").val());
        var dval = parseInt($("#apack_general").val());
        var mval = parseInt($("#min_apack").html());
        cval += price;
        $("#apack_cust").html(cval);
        $(
            "#cu_apack").val(cval);
        if (cval > dval) {
            $("#apack_st").html(cval);
            $("#st_apack").val(cval);
        } else {
            $("#apack_st").html(dval);
            $("#st_apack").val(dval);
        }
        if (cval < mval) {
            $('#cu_apack_disc').val(0);
            $('#apack_disc_cust').html(0);
            $('#c_apack_disc').html(0);
        } else {
            var dd = parseInt($("#apack_general_disc").val());
            $('#cu_apack_disc').val(dd);
            $('#apack_disc_cust').html(dd);
            $('#c_apack_disc').html(dd);
        }

        updateValues();
    });


    $('#c_apack').on('select2:unselect', function (e) {
        let price = $(e.params.data.element).data('price');
        let aval = $(e.params.data.element).val();
        let atxt = $(e.params.data.element).text();
        var aitm = atxt.split("@");
        //alert("Accessories Selected : " + aval + ", Price : " + price + ", Text : " + aitm[0]
        //.trim());
        var prmt = aval + "||" + price + "||" + aitm[0].trim();
        var tmp = $("#cu_apack_details").val();
        var apar = tmp.split("###");
        var apis = apar.indexOf(prmt);
        if (apis > -1) { // only splice array when item is found
            apar.splice(apis, 1); // 2nd parameter means remove one item only
        }
        $("#cu_apack_details").val(apar.join("###"));
        //alert($("#cu_apack_details").val());
        var cval = parseInt($("#cu_apack").val());
        var dval = parseInt($("#apack_general").val());
        var mval = parseInt($("#min_apack").val());
        cval -= price;
        $("#apack_cust").html(cval);
        $(
            "#cu_apack")
            .val(cval);
        if (cval > dval) {
            $("#apack_st").html(cval);
            $("#st_apack").val(cval);
        } else {
            $("#apack_st").html(dval);
            $("#st_apack").val(dval);
        }
        if (cval < mval) {
            $('#cu_apack_disc').val(0);
            $('#apack_disc_cust').html(0);
            $('#c_apack_disc').html(0);
        } else {
            var dd = parseInt($("#apack_general_disc").val());
            $('#cu_apack_disc').val(dd);
            $('#apack_disc_cust').html(dd);
            $('#c_apack_disc').html(dd);
        }
        updateValues();
    });

});
