<template>
	<div>
		<div class="content">
			<div class="container">
                <div class="login_wrapper">
                    <div class="login_header">
                        <h2>Login Panel</h2>
                    </div>
                    <div class="login_body">
                        <div class="spacer">
                            <Input prefix="ios-mail-outline" type="email" clearable v-model="data.email" placeholder="E-Mail" style="width: 100%" />
                        </div>
                        <div class="spacer">
                            <Input prefix="ios-lock" type="password" clearable v-model="data.password" placeholder="Password" style="width: 100%" />
                        </div>
                    </div>
                    <div class="login_footer">
                        <Button type="primary" @click="login" :disabled="isLoggingIn" :loading="isLoggingIn">{{ isLoggingIn ? 'Logging in..' : 'Login'}}</Button>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</template>        

<style scoped>
    .spacer{
        margin : 5px 5px;
    }
    .login_wrapper{
        margin: 0 21%;
        margin-top: 10%;
        background: #ffffff;
        padding: 15px;
        line-height: 35px;
        text-align: center;
        width: 35%;
        border-radius: 1%;
        box-shadow: 0px 5px 4px grey;
    }
    .login_header{
        margin: 4%;
    }
    .login_footer{
        margin-top:3%;
    }
</style>

<script>
export default {
    data(){
        return {
            data:{
                email:'',
                password:'',
            },
            isLoggingIn: false,
        }
    },
    methods:{
        async login(){
            if(this.data.email.trim()=='') return this.e('E-mail is required')
            if(this.data.password.trim()=='') return this.e('Password is required')
            if(this.data.password.length<6) return this.e('Password atleast 6 characters')
            this.btnLoadingOn();
            const res = await this.callApi('post', '/user/login/attempt', this.data);
            if(res.status===200){
                this.s(res.data.msg, 'Welcome');
                window.location = '/dashboard';
            } else if(res.status===422) {
                for(let i in res.data.errors) this.e(res.data.errors[i])
            } else if(res.status===401) {
                this.e(res.data.msg, 'Login Failed');
            } else {
                this.e();
                //console.log(res);
            }
            this.btnLoadingOff();
        },
        btnLoadingOn(){
            this.isLoggingIn = true;
        },
        btnLoadingOff(){
            this.isLoggingIn = false;
        },
    },
}
</script>