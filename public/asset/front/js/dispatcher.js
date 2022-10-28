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
        
        this.forceUpdate();
      
    }

    handleRequestShow(trip) {
		
        //console.log('Show Request123', trip); 
        //.attr('id')
		
        let curent_active_tab = $("#process-filters li.active").attr('id');
           
        if(trip.current_provider_id == 0) {
            this.setState({
                listContent: 'dispatch-assign',
                trip: trip
            });
        } else if(trip.status == 'PICKEDUP' && curent_active_tab!='dispatch-map'){

           this.setState({
                listContent: 'dispatch-dispatching',
                trip: trip
            });
        }else if(trip.status == 'SEARCHING' && curent_active_tab!='dispatch-map'){

           this.setState({
                listContent: 'dispatch-new',
                trip: trip
            });
        }else if(trip.status == 'COMPLETED' && curent_active_tab!='dispatch-map'){

           this.setState({
                listContent: 'dispatch-completed',
                trip: trip
            });
        }else if(trip.status == 'CANCELLED' && curent_active_tab!='dispatch-map'){

           this.setState({
                listContent: 'dispatch-cancelled',
                trip: trip
            });
        }else if(trip.status == 'SCHEDULED' && curent_active_tab!='dispatch-map'){

           this.setState({
                listContent: 'dispatch-scheduled',
                trip: trip
            });
        }else{
            this.setState({
                listContent: 'dispatch-map',
                trip: trip
            });
        }
		
		
        /// dispatch-new    dispatch-completed    dispatch-cancelled   dispatch-scheduled

        ongoingInitialize(trip);
        
    }

   componentWillUnmount() {
        clearInterval(window.PinTaxi.TripTimer);
    }


    handleRequestCancel(argument) {
        this.setState({
            listContent: 'dispatch-map'
        });
    }

    render() {

         let listContent = null;
         //dispatcherAcive();
         //console.log('DispatcherPanel 1233'+listContent, this.state.listContent);
          

        switch(this.state.listContent) {
            case 'dispatch-create':
                listContent = <div className="col-md-4">
                        <DispatcherRequest completed={this.handleRequestShow.bind(this)} cancel={this.handleRequestCancel.bind(this)} />
                    </div>;
                break; 
            case 'dispatch-map':
                listContent = <div className="col-md-4">
                        <DispatcherList clicked={this.handleRequestShow.bind(this)} />
                    </div>;
                break;

            case 'dispatch-new':
                listContent = <div className="col-md-4">
                        <DispatcherListFilter filter={this.state.listContent} clicked={this.handleRequestShow.bind(this)} />
                    </div>;
                break;

            case  'dispatch-dispatching':
                listContent = <div className="col-md-4">
                        <DispatcherListFilter filter={this.state.listContent} clicked={this.handleRequestShow.bind(this)} />
                    </div>;
                break;

             case  'dispatch-completed':
                listContent = <div className="col-md-4">
                        <DispatcherListFilter filter={this.state.listContent} clicked={this.handleRequestShow.bind(this)} />
                    </div>;
                break;

            case  'dispatch-cancelled':
                listContent = <div className="col-md-4">
                        <DispatcherListFilter filter={this.state.listContent} clicked={this.handleRequestShow.bind(this)} />
                    </div>;
                break;
            case  'dispatch-scheduled':
                listContent = <div className="col-md-4">
                        <DispatcherListFilter filter={this.state.listContent} clicked={this.handleRequestShow.bind(this)} />
                    </div>;
                break;

            case 'dispatch-assign':
                listContent = <div className="col-md-4">
                        <DispatcherAssignList trip={this.state.trip} />
                    </div>;
                break;
                default:
               listContent = <div className="col-md-4">
                        <DispatcherList  clicked={this.handleRequestShow.bind(this)} />
                    </div>;
                break;
        }


        return (
            <div className="container-fluid">
                <h4>Recent Trips</h4>

                <DispatcherNavbar
                	body={this.state.listContent}
                	updateBody={this.handleUpdateBody.bind(this)}
                	updateFilter={this.handleUpdateFilter.bind(this)}
                />

                <div className="row">
                    { listContent } 
                    <div className="col-md-8">
                        <DispatcherMap body={this.state.listContent} />
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


    filter(body, event) {
        this.setState({body})
        this.props.updateFilter(body);
        this.forceUpdate();
    }

    componentWillUpdate(nextProps, nextState) {}

    //componentwillreceiveprops(newProps) {}

    handleClick(data){ 
       dispatcherAcive();
    }
    handleBodyChange() {
       ///console.log('handleBodyChange', this.state);
     dispatcherAcive();
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
        }else if(this.state.body == 'dispatch-completed'){

            this.props.updateBody('dispatch-completed');
            this.setState({
                body: 'dispatch-completed'
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
                <button className="navbar-toggler hidden-md-up" 
                    data-toggle="collapse"
                    data-target="#process-filters"
                    aria-controls="process-filters"
                    aria-expanded="false"
                    aria-label="Toggle Navigation"></button>

                <form className="form-inline navbar-item ml-1 float-xs-right">
                    <div className="input-group">
                        <input type="text" className="form-control b-a" placeholder="Search for..." />
                        <span className="input-group-btn">
                            <button type="submit" className="btn btn-primary b-a">
                                <i className="ti-search"></i>
                            </button>
                        </span>
                    </div>
                </form> 

                 

                <div className="collapse navbar-toggleable-sm" id="process-filters">
                    <ul className="nav navbar-nav dispatcher-nav">
                        <li className="nav-item active dispatcher-tab" id="dispatch-map" onClick={(e)=>this.filter('dispatch-map', e)}>
                            <span className="nav-link dispatcher-link">All</span>
                        </li>
                        <li className="nav-item dispatcher-tab" id="dispatch-new" onClick={(e)=>this.filter('dispatch-new', e)}>
                            <span className="nav-link dispatcher-link">New</span>
                        </li>
                        <li className="nav-item dispatcher-tab" id="dispatch-dispatching" onClick={(e)=> this.filter('dispatch-dispatching', e)}>
                            <span className="nav-link dispatcher-link">Dispatching</span>
                        </li>
                        <li className="nav-item dispatcher-tab" id="dispatch-completed" onClick={(e)=>this.filter('dispatch-completed', e)}>
                            <span className="nav-link dispatcher-link">Completed</span>
                        </li>
                        <li className="nav-item dispatcher-tab" id="dispatch-cancelled" onClick={(e)=>this.filter('dispatch-cancelled', e)}>
                            <span className="nav-link dispatcher-link" >Cancelled</span>
                        </li>
                        <li className="nav-item dispatcher-tab" id="dispatch-scheduled" onClick={(e)=>this.filter('dispatch-scheduled', e)}>
                            <span className="nav-link dispatcher-link">Scheduled</span>
                        </li>
                    </ul>
                </div>
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
         dispatcherAcive();
        // Refresh trip details
        window.PinTaxi.TripTimer = setInterval(

            () => this.getTripsUpdate(),
            1000
        );
    }

    componentWillUnmount() {
        clearInterval(window.PinTaxi.TripTimer);
    }

    getTripsUpdate() {
        $.get('/dispatcher/dispatcher/trips', function(result) {
            // console.log('Trips', result.hasOwnProperty('data'));
            if(result.hasOwnProperty('data')) {
               // tripUpdate(result.data);
                this.setState({
                    data: result
                });
            } else {
                // Might wanna show an empty list when this happens
                this.setState({
                    data: {
                        data: []
                    }
                });
            }
        }.bind(this));
    }

    handleClick(trip) {
        console.log('hi...elo...........');

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
            
    componentWillReceiveProps(newProps) {
    	this.updateFilter(newProps);
    }


    componentDidMount() {
        this.updateFilter(this.props)
    }

    updateFilter(props) {
    	$.get('dispatcher/trips/', { 
             filter: props.filter
        }, function(result) {
           // console.log('Trips', result);
            if(result.hasOwnProperty('data')) {
                this.setState({
                    data: result
                });
                window.assignProviderShow(result.data, props.trip);
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
         console.log('click ..');
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
    	 console.log("request id", trip.id);
        this.props.clicked(trip)
		
    }

    _cancel(trip) {
        // event.preventDefault();
       // console.log("Reason123", trip.id);
        //alert(trip.id);

        if(confirm("Are you sure want to cancel trip!")){
	        $.ajax({
	            url: bases_url+'/dispatcher/discancel/ride',
	            dataType: 'json',
	            data: {cancel_reason:'Let Respose!', request_id: trip.id},
	            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
	            type: 'POST',
	            success: function(data) {
	                window.location.replace(bases_url+"/dispatcher/recent-trips");
	            }.bind(this),
	            error: function(xhr) {
	                window.location.replace(bases_url+"/dispatcher/recent-trips");
	            }.bind(this)
	        });
	     }
    }


    _dead(trip) {
        // event.preventDefault();
       // console.log("Reason123", trip.id);
        //alert(trip.id);

        if(confirm("Are you sure want to dead trip!")){
	        $.ajax({
	            url: bases_url+'/dispatcher/discancel/ride',
	            dataType: 'json',
	            data: {cancel_reason:'Let Respose!', request_id: trip.id},
	            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
	            type: 'POST',
	            success: function(data) {
	                window.location.replace(bases_url+"/dispatcher/recent-trips");
	            }.bind(this),
	            error: function(xhr) {
	                window.location.replace(bases_url+"/dispatcher/recent-trips");
	            }.bind(this)
	        });
	    }
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
                                     
                                     
                                    <span className="text-muted">{trip.current_provider_id == 0 ? "Manual Assignment" : "Auto Search" } : {trip.created_at}</span>
                                </div>
                            </div>
                            {trip.status == 'SEARCHING' ?  
                                        <button type="button" className="btn btn-danger"   onClick={this._cancel.bind(this, trip)}>Cancel</button>

                                                 : " "
                                     }
                                     |
                            {trip.status == 'SEARCHING' ?  
                                        <button type="button" className="btn btn-danger"   onClick={this._dead.bind(this, trip)}>Dead</button>

                                                 : " "
                                     }


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
		            url: bases_url+'/dispatcher/dispatcher/provider_list',
		            dataType: 'json',
		            headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken },
		            type: 'POST',
		            data: $("#form-create-ride").serialize(),
		            success: function(data) {
		                  console.log(data);
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
            url: bases_url+'/dispatcher/dispatcher',
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
						<div className="col-xs-6">
							<div className="form-group" onChange={this.setBookingType.bind(this)}>  
								<input type="radio" id="booking_typeq" name="booking_type" value="1" className="booking_type"   defaultChecked/>
								<label htmlFor="booking_type">Indivisual</label>
								<input type="radio" id="booking_type" name="booking_type" value="2" className="booking_type" />
								<label htmlFor="booking_typeq"> Corporate </label>
							</div>
		                    <div className="form-group hide" id="corporate_list"></div>
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
                                <input type="email" className="form-control" name="email" id="email" placeholder="Email" />
                            </div>
                        </div>
                        <div className="col-xs-6">
                            <div className="form-group">
                                <label htmlFor="mobile">Phone</label>
                                <input type="text" className="form-control" name="mobile" id="mobile" placeholder="Phone" required />
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
                                <input type="text" className="form-control form_datetime" name="schedule_time" id="schedule_time" placeholder="Date" required/>
                            </div>
                            <div className="form-group">
                                <label htmlFor="service_types">Service Type</label>
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
                            <button className="btn btn-lg btn-success btn-block waves-effect waves-light">
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
    render() {
        // console.log('ServiceTypes', this.props.data);
        var mySelectOptions = function(result) {
            return <ServiceTypesOption
                    key={result.id}
                    id={result.id}
                    name={result.name} />
        };
        return (
                <select 
                    name="service_type"
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