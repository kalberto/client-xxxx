const { component, section, template, view } = require('./config');
const { generateTemplateFiles } = require('generate-template-files');
let args = process.argv.slice(2);
let configs = [component, section, template, view];

if (args.length > 0) {
	args.forEach((arg, index) => {
		arg = arg.slice(2);
		arg = arg[0].toUpperCase() + arg.slice(1)
		args[index] = arg
	});
	configs = configs.filter(config => config.option == args[0])
}

generateTemplateFiles(configs);
