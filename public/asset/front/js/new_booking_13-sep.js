'use strict';

class DispatcherPanel extends React.Component {
    componentWillMount() {
        this.setState({
            listContent: 'dispatch-map'
        });
    }

    handleUpdateBody(body) {
        console.log('Body Update Called', body);
        this.setState({
            listContent: body
        });
    }

    handleUpdateFilter(filter) {
        console.log('Filter Update Called', filter);
        this.setState({
            listContent: filter
        });
    }

    handleRequestShow(trip) {
        
            this.setState({
                listContent: 'dispatch-map',
                trip: trip
            });
       
        ongoingInitialize(trip);
    }

    handleRequestCancel(argument) {
        this.setState({
            listContent: 'dispatch-map'
        });
    }

    render() {

        let listContent = null;

         console.log('DispatcherPanel', this.state.listContent);

       
             
               listContent = <div className="col-md-4">
                        <DispatcherRequest completed={this.handleRequestShow.bind(this)} cancel={this.handleRequestCancel.bind(this)} />
                    </div>;
               
        

        return (
            <div className="container-fluid">
                <h4>New Booking</h4>

                <DispatcherNavbar body={this.state.listContent} updateBody={this.handleUpdateBody.bind(this)} updateFilter={this.handleUpdateFilter.bind(this)}/>

                <div className="row">
                    { listContent }

                    <div className="col-md-8">
                        <DispatcherMap body={this.state.listContent} />
                    </div>
                     <div className="col-md-8" id="fareMap">
                     </div>
                </div>
            </div>
        );
    }
};

class DispatcherNavbar extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            body: 'dispatch-map'
        };
    }

    filter(data) {
        console.log('Navbar Filter', data);
        this.props.updateFilter(data);
    }

    handleBodyChange() {
        ////console.log('handleBodyChange', this.state);
        if(this.props.body != this.state.body) {
            this.setState({
                body: this.props.body
            });
        }

        if(this.state.body == 'dispatch-map') {
            this.props.updateBody('dispatch-create');
            this.setState({
                body: 'dispatch-create'
            });
        }else{

            this.props.updateBody('dispatch-map');
            this.setState({
                body: 'dispatch-map'
            });
        }
    }

    render() {
        return (
            <nav className="navbar navbar-light bg-white b-a mb-2"> 
                 
            </nav>
        );
    }
}

class DispatcherList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: {
                data: []
            }
        };
    }

    componentDidMount() {
        // Mount Global Map
        window.worldMapInitialize();

        
    } 
    

    handleClick(trip) {
        this.props.clicked(trip);
    }

    render() {
        // console.log(this.state.data);
        return (
            <div className="card">
                <div className="card-header text-uppercase"><b>List</b></div>
                
                <DispatcherListItem data={this.state.data.data} clicked={this.handleClick.bind(this)} />
            </div>
        );
    }
}


class DispatcherListFilter extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: {
                data: []
            }
        };
    }  
            
             


     componentDidMount() {

        console.log('Trips', this.props.filter);

        $.get('dispatcher/dispatcher/trips/', { 
             filter:this.props.filter
        }, function(result) {
           // console.log('Trips', result);
            if(result.hasOwnProperty('data')) {
                this.setState({
                    data: result
                });
                 //window.assignProviderShow(result.data, this.props.trip);
            } else {
                this.setState({
                    data: {
                        data: []
                    }
                });
                window.providerMarkersClear();
            }
        }.bind(this));
    }
    

    handleClick(trip) {
        this.props.clicked(trip);
    }

    render() {
        // console.log(this.state.data);
        return (
            <div className="card">
                <div className="card-header text-uppercase"><b>List</b></div>
                
                <DispatcherListItem data={this.state.data.data} clicked={this.handleClick.bind(this)} />
            </div>
        );
    }
}

class DispatcherListItem extends React.Component {
    handleClick(trip) {
        this.props.clicked(trip)
    }
    render() {
        var listItem = function(trip) {
            return (
                    <div className="il-item" key={trip.id} onClick={this.handleClick.bind(this, trip)}>
                        <a className="text-black" href="#">
                            <div className="media">
                                <div className="media-body">
                                    <p className="mb-0-5">{trip.user.first_name} {trip.user.last_name} 
                                    {trip.status == 'COMPLETED' ?
                                        <span className="tag tag-success pull-right"> {trip.status} </span>
                                    : trip.status == 'CANCELLED' ?
                                        <span className="tag tag-danger pull-right"> {trip.status} </span>
                                    : trip.status == 'SEARCHING' ?
                                        <span className="tag tag-warning pull-right"> {trip.status} </span>
                                    : trip.status == 'SCHEDULED' ?
                                        <span className="tag tag-primary pull-right"> {trip.status} </span>
                                    : 
                                        <span className="tag tag-info pull-right"> {trip.status} </span>
                                    }
                                    </p>
                                    <h6 className="media-heading">From: {trip.s_address}</h6>
                                    <h6 className="media-heading">To: {trip.d_address ? trip.d_address : "Not Selected"}</h6>
                                    <h6 className="media-heading">Payment: {trip.payment_mode}</h6>
                                      {trip.status == 'SEARCHING' ?  
                                        <progress className="progress progress-success progress-sm" max="100"></progress>

                                         : " "
                                     }
                                     
                                    <span className="text-muted">{trip.current_provider_id == 0 ? "Manual Assignment" : "Auto Search"} : {trip.created_at}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                );
        }.bind(this);

        return (
            <div className="items-list">
                {this.props.data.map(listItem)}
            </div>
        );
    }
}

class DispatcherRequest extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            data: []
        };
    }

    componentDidMount() {

        // Auto Assign Switch
      var init =  new Switchery(document.getElementById('provider_auto_assign'));
      var changeCheckbox = document.querySelector('.js-check-change');

		changeCheckbox.onchange = function() {


		  if(!changeCheckbox.checked){

                    
				
				var html ='';
		        //event.stopPropagation();
		       // console.log('Hello', $("#form-create-ride").serialize());
		        $.ajax({
		            url: '/dispatcher/dispatcher/provider_list',
		            dataType: 'json',
		            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
		            type: 'POST',
		            data: $("#form-create-ride").serialize(),
		            success: function(data) {
		               
		                   html+='<label htmlFor="provider_id">Select Provider</label>';
		                   html+='<select name="provider_id" class="form-control">';

                           
                           $.each(data.data, function(i, item) {
                           	  //console.log('provider list', item);
                               html+='<option value="'+item.id+'"> '+item.first_name+' '+item.last_name+' </option>';
                           });

                           html+='</select>';

                           $("#provider_list").html(html);

                           $("#provider_list").show();
		            }.bind(this)
		        });
		  }else{

                $("#provider_list").hide();
		  }

		};
        
        
        // Schedule Time Datepicker
        
        $('#schedule_time').datetimepicker({
             useCurrent: false,
            minDate: window.PinTaxi.minDate,
            maxDate: window.PinTaxi.maxDate,
        });

 
        // Get Service Type List
        $.get('/dispatcher/service', function(result) {
            this.setState({
                data: result
            });
        }.bind(this));

        // Mount Ride Create Map
 
        window.createRideInitialize();

        function stopRKey(evt) { 
            var evt = (evt) ? evt : ((event) ? event : null); 
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null); 
            if ((evt.keyCode == 13) && (node.type=="text"))  {return false;} 
        } 

        document.onkeypress = stopRKey; 
    }

    createRide(event) {
        console.log(event);
        event.preventDefault();
        event.stopPropagation();
        console.log('Hello', $("#form-create-ride").serialize());
        $.ajax({
            url: '/dispatcher/dispatcher',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: 'POST',
            data: $("#form-create-ride").serialize(),
            success: function(data) {
                console.log('Accept', data);
                this.props.completed(data);
            }.bind(this)
        });
    }

    cancelCreate() {
        this.props.cancel(true);

    }

    msgCreate() {
        
       alert("Your Request has been sent ! Please wait system is assigning the nearnest Driver");
       window.location = base_url+'/dispatcher/recent-trips';
    }

    setBookingType(event){ 
    	 
    	console.log(event.target.value);
    	 // Get Providers List
    	  var html='';
    	    if(event.target.value=='2'){   
               
		        $.get('/dispatcher/corporate_list', function(result) {
		             
		             console.log(result);
		              html+='<label htmlFor="provider_id">Select Corporate</label>';
		                   html+='<select name="corporate_id" class="form-control">'; 

                           $.each(result, function(i, item) {
                           	  //console.log('provider list', item);
                               html+='<option value="'+item.id+'"> '+item.name+' </option>';
                           });

                           html+='</select>';

                           $("#corporate_list").html(html);

		              $("#corporate_list").show();

		        }.bind(this));

		    }else{
               $("#corporate_list").hide();
		    } 
    } 



    render() {
        return (
            <div className="card card-block" id="create-ride">
                <h3 className="card-title text-uppercase">Ride Details</h3>
                <form id="form-create-ride" onSubmit={this.createRide.bind(this)} method="POST">
                    <div className="row">
                          
		                        <div className="form-group" onChange={this.setBookingType.bind(this)}>  
                                       <label className="btn btn-secondary active"  >
                                    <input type="radio" id="booking_typeq" name="booking_type" value="1" className="booking_type"   defaultChecked/>
                                    Indivisual
                                    </label>

                                    <label className="btn btn-secondary" >
                                    <input type="radio" id="booking_type"  name="booking_type" value="2" className="booking_type" />
                                    Corporate </label>
		                        </div>
                        


		                             <div className="form-group hide" id="corporate_list">
		                            </div>
                         
                        <div className="col-xs-6">
                            <div className="form-group">
                                <label htmlFor="first_name">First Name</label>
                                <input type="text" className="form-control" name="first_name" id="first_name" placeholder="First Name" required />
                            </div>
                        </div>
                        <div className="col-xs-6">
                            <div className="form-group">
                                <label htmlFor="last_name">Last Name</label>
                                <input type="text" className="form-control" name="last_name" id="last_name" placeholder="Last Name" />
                            </div>
                        </div>
                        <div className="col-xs-6">
                            <div className="form-group">
                                <label htmlFor="email">Email</label>
                                <input type="email" className="form-control" name="email" id="email" placeholder="Email"  />
                            </div>
                        </div>
                        <div className="col-xs-6">
                            <div className="form-group">
                                <label htmlFor="mobile">Phone</label>
                                <input type="text" className="form-control" name="mobile" id="mobile" placeholder="Phone"   />
                            </div>
                        </div>
                        <div className="col-xs-12">
                            <div className="form-group">
                                <label htmlFor="s_address">Pickup Address</label>
                                
                                <input type="text"
                                    name="s_address"
                                    className="form-control"
                                    id="s_address"
                                    placeholder="Pickup Address"
                                    required></input>

                                <input type="hidden" name="s_latitude" id="s_latitude"></input>
                                <input type="hidden" name="s_longitude" id="s_longitude"></input>
                            </div>
                            <div className="form-group">
                                <label htmlFor="d_address">Dropoff Address</label>
                                
                                <input type="text" 
                                    name="d_address"
                                    className="form-control"
                                    id="d_address"
                                    placeholder="Dropoff Address"
                                    required></input>

                                <input type="hidden" name="d_latitude" id="d_latitude"></input>
                                <input type="hidden" name="d_longitude" id="d_longitude"></input>
                                <input type="hidden" name="distance" id="distance"></input>
                            </div>
                            <div className="form-group">
                                <label htmlFor="schedule_time">Schedule Time</label>
                                <input type="text" className="form-control" name="schedule_time" id="schedule_time" placeholder="Date" />
                            </div>
                            <div className="form-group">
                                <label htmlFor="service_types">Cab Type</label>
                                <ServiceTypes data={this.state.data} />
                            </div> 

                            <div className="form-group">
                                <label htmlFor="provider_auto_assign">Auto Assign Provider</label>
                                <br />
                                <input  type="checkbox" id="provider_auto_assign" name="provider_auto_assign" className="js-check-change" data-color="#f59345" defaultChecked checked={this.state.isChecked} />
                            </div>
                             <div className="form-group hide" id="provider_list">
		                            </div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-xs-6">
                            <button type="button" className="btn btn-lg btn-danger btn-block waves-effect waves-light" onClick={this.cancelCreate.bind(this)}>
                                CANCEL
                            </button>
                        </div>
                        <div className="col-xs-6">
                            <button className="btn btn-lg btn-success btn-block waves-effect waves-light" onClick={this.msgCreate.bind(this)}>
                                SUBMIT
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        );
    }
};

class DispatcherAssignList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: {
                data: []
            }
        };
    }

    componentDidMount() {
        $.get('/dispatcher/dispatcher/providers', { 
            latitude: this.props.trip.s_latitude,
            longitude: this.props.trip.s_longitude
        }, function(result) {
            console.log('Providers', result);
            if(result.hasOwnProperty('data')) {
                this.setState({
                    data: result
                });
                window.assignProviderShow(result.data, this.props.trip);
            } else {
                this.setState({
                    data: {
                        data: []
                    }
                });
                window.providerMarkersClear();
            }
        }.bind(this));
    }

    render() {
        console.log('DispatcherAssignList - render', this.state.data);
        return (
            <div className="card">
                <div className="card-header text-uppercase"><b>Assign Provider</b></div>
                
                <DispatcherAssignListItem data={this.state.data.data} trip={this.props.trip} />
            </div>
        );
    }
}

class DispatcherAssignListItem extends React.Component {
    handleClick(provider) {
        // this.props.clicked(trip)
        console.log('Provider Clicked');
        window.assignProviderPopPicked(provider);
    }
    render() {
        var listItem = function(provider) {
            return (
                    <div className="il-item" key={provider.id} onClick={this.handleClick.bind(this, provider)}>
                        <a className="text-black" href="#">
                            <div className="media">
                                <div className="media-body">
                                    <p className="mb-0-5">{provider.first_name} {provider.last_name}</p>
                                    <h6 className="media-heading">Rating: {provider.rating}</h6>
                                    <h6 className="media-heading">Phone: {provider.mobile}</h6>
                                    <h6 className="media-heading">Type: {provider.service.service_type.name}</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                );
        }.bind(this);

        return (
            <div className="items-list">
                {this.props.data.map(listItem)}
            </div>
        );
    }
}

class ServiceTypes extends React.Component { 
	 
   handleChange = (event) => {
   	   var HTML ='';

	   $.ajax({
            url: '/dispatcher/dispatcher/getFare',
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
            type: 'POST',
            data: $("#form-create-ride").serialize(),
            success: function(data) {
                HTML  +='<div class="row no-margin"><div class="col-md-12"><ul class="list-group">';
                HTML  +='<li class="list-group-item d-flex justify-content-between align-items-center">Type: <span class="badge badge-primary badge-pill">'+data.service.name+'</span></li><li class="list-group-item d-flex justify-content-between align-items-center">Total Distance: <span class="badge badge-primary badge-pill">'+data.distance+' Kilometer</span></li>';
                HTML  +='<li class="list-group-item d-flex justify-content-between align-items-center">ETA: <span class="badge badge-primary badge-pill">'+data.time+'</span></li> <li class="list-group-item d-flex justify-content-between align-items-center"> Estimated Fare:  <span class="badge badge-primary badge-pill">$'+data.estimated_fare+'</span></li></ul></div></div>';
                  $('#fareMap').html(HTML);
                console.log(data);
            } 
        });
  };
    render() {
        // console.log('ServiceTypes', this.props.data);
        var mySelectOptions = function(result) {
            return <ServiceTypesOption
                    key={result.id}
                    id={result.id}
                    name={result.name} />
        };
        return (
                <select onChange={this.handleChange}  
                    name="service_type"
                    id="service_type"
                    className="form-control">
                    {this.props.data.map(mySelectOptions)}
                </select>
            )
    }
}

class ServiceTypesOption extends React.Component {
    render() {
        return (
            <option value={this.props.id}>{this.props.name}</option>
        );
    }
};

class DispatcherMap extends React.Component {
    componentDidMount() {
        //// Mount Global Map
       // window.worldMapInitialize();

        // Refresh trip details
        window.PinTaxi.TripTimer = setInterval(
            () => this.getTripsUpdate(),
            3000
        );


    }

      
    getTripsUpdate() {
        $.get('/dispatcher/dispatcher/providers',{
             latitude:($("#s_latitude").val())?$("#s_latitude").val():current_Lat,
             longitude:($("#s_longitude").val())?$("#s_longitude").val():current_long,
             service_type:$("#service_type").val()
        }, function(result) {

             ProviderShow(result.data);
            if(result.hasOwnProperty('data')) {
                this.setState({
                    trip: result
                });

            } else {
                // Might wanna show an empty list when this happens
                this.setState({
                    data: {
                        trip: []
                    }
                });
            }
        }.bind(this));

        
    }

    render() {
        return (
            <div className="card my-card">
                <div className="card-header text-uppercase">
                    <b>MAP</b>
                </div>
                <div className="card-body">
                    <div id="map" style={{ height: '450px'}}></div>
                </div>
            </div>
        );
    }
}

ReactDOM.render(
    <DispatcherPanel />,
    document.getElementById('dispatcher-panel')
);

class ModalComponent extends React.Component {

    componentDidUpdate(prevProps, prevState) {
        // console.log('Modal Component Updated');
        if(this.props.request.status == "SEARCHING") {
            this._open();
        }
    } 
     

    _open() {
        console.log('Modal Show');
        $("#wait-modal").modal('show');
    }

    _close() {
        console.log('Modal Hide');
        $("#wait-modal").hide('hide');
    }

    render() {
      
        return (
            <div className="modal fade" id="wait-modal" role="dialog">
                <div className="modal-dialog" role="document">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h4 className="modal-title text-center incoming-tit" id="myModalLabel">Please Wait</h4>
                        </div>
                        <div className="modal-body">
                            <div className="incoming-img bg-img" id="user-image" style={{ backgroundImage: 'url(' + base_url+"/storage/app/public/"+this.props.request.user.picture + ')' }}></div>
                            <div className="text-center">
                                <h3 id="usser-name"> </h3>
                                {this.props.request.schedule_at ?
                                    <h5>Scheduled At : {this.props.request.schedule_at}</h5>
                                    : ""
                                }
                            </div>
                        </div>
                        <div className="modal-footer row no-margin">
                            <button type="button" className="btn btn-primary incoming-btn" onClick={this._accept.bind(this)}>Accept</button>
                            <button type="button" className="btn btn-default incoming-btn" onClick={this._reject.bind(this)} data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
};
