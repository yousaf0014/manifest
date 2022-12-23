import React, {Component} from 'react';
import {browserHistory} from 'react-router';
import MyGlobleSetting from './MyGlobleSetting';
class CreateUser extends Component {
  constructor(props){
    super(props);
    this.state = {firstname: '', lastaname: '',email:'',password:'',confirmpassword:''};
    this.handleChangeFirstName = this.handleChangeFirstName.bind(this);
    this.handleChangeLastName = this.handleChangeLastName.bind(this);
    this.handleChangeEmail = this.handleChangeEmail.bind(this);
    this.handleChangePassword = this.handleChangePassword.bind(this);
    this.handleChangeCPassword = this.handleChangeCPassword.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }
  handleChangeFirstName(e){
    this.setState({
      firstname: e.target.value
    })
  }
  handleChangeLastName(e){
    this.setState({
      lastaname: e.target.value
    })
  }
  handleChangeEmail(e){
    this.setState({
      email: e.target.value
    })
  }
  handleChangePassword(e){
    this.setState({
      password: e.target.value
    })
  }
  handleChangeCPassword(e){
    this.setState({
      confirmpassword: e.target.value
    })
  }

  handleSubmit(e){
    e.preventDefault();
    const products = {
      first_name: this.state.firstname,
      last_name: this.state.lastaname,
      email: this.state.email,
      password: this.state.password,
      c_password: this.state.confirmpassword,
      mobile_key:'yYgIvy8fK127X9GpSlepkuJmy7c7f7rB7p7Tn08lGzo0'
    }

    let uri = MyGlobleSetting.url + '/api/register';
    axios.post(uri, products).then((response) => {
      browserHistory.push('/display-item');
    });
  }

  render() {
    return (
      <div>
        <h1>Create User</h1>
        <form onSubmit={this.handleSubmit}>
          <div className="row">
            <div className="col-md-6">
              <div className="form-group">
                <label>First Name:</label>
                <input type="text" className="form-control" onChange={this.handleChangeFirstName} />
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-md-6">
              <div className="form-group">
                <label>Last Name:</label>
                <input type="text" className="form-control" onChange={this.handleChangeLastName} />
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-md-6">
              <div className="form-group">
                <label>Email:</label>
                <input type="email" className="form-control" onChange={this.handleChangeEmail} />
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-md-6">
              <div className="form-group">
                <label>Password:</label>
                <input type="password" className="form-control" onChange={this.handleChangePassword} />
              </div>
            </div>
          </div>
          <div className="row">
            <div className="col-md-6">
              <div className="form-group">
                <label>Confirm Password:</label>
                <input type="password" className="form-control" onChange={this.handleChangeCPassword} />
              </div>
            </div>
          </div>
          <br />
          <div className="form-group">
            <button className="btn btn-primary">Add User</button>
          </div>
        </form>
      </div>
    )

  }
}
export default CreateUser