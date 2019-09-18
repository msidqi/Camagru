function getData(url, callback) {
	setTimeout(() => {
		callback('data');
	}, 3000);
}

const getData = (url) => {
	new Promise((reslove, reject) => {
		setTimeout(() => {
			reslove('data');
			// reject(new Error('error mssg.'));
		}, 3000);
	});
}

const ft = async () => {
	try {
		const data = await getData('');
		const data2 = await getData2('');
	}
	catch(err) {
		console.log(err.message);
	}
}

getData('url')
	.then((data) => {})
	.catch((err) => {})

getData('', (data) => {
	console.log(data);
});
