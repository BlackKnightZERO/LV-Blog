export default{
    data(){
        return{

        }
    },
    methods:{

        async callApi(method, url, dataObj){
            try {
               return await axios({
                    method: method,
                    url: url,
                    data: dataObj,
                  });
            } catch (e) {
                return e.response
            }
        },

        i(desc,title="Hey!") {
			this.$Notice.info({
				title: title,
				desc: desc,
			});
		},
		s(desc,title="Great!") {
			this.$Notice.success({
				title: title,
				desc: desc,
			});
		},
		w(desc,title="Knock!") {
			this.$Notice.warning({
				title: title,
				desc: desc,
			});
		},
		e(desc="Something Went Wrong! Pleae Try Again!", title="Oops!") {
			this.$Notice.error({
				title: title,
				desc: desc,
			});
        },
        
    },
}