#include <stdio.h>
#include <rawtext.h>
#include <swmgr.h>
#include <versekey.h>
#include <markupfiltmgr.h>
#include <regex.h> // GNU
#include <iostream>
#include <sstream>
#include <string>
#include <iomanip>

#ifndef NO_SWORD_NAMESPACE
using namespace sword;
#endif

using std::cout;
using std::endl;

std::string convert_string(std::string s) {
    std::stringstream ss;
    for (size_t i = 0; i < s.length(); ++i) {
        if (unsigned(s[i]) < '\x20' || s[i] == '\\' || s[i] == '"') {
            ss << "\\u" << std::setfill('0') << std::setw(4) << std::hex << unsigned(s[i]);
        } else {
            ss << s[i];
        }
    }
    return ss.str();
}

int main(int argc, char **argv) {
	SWMgr manager;
        SWModule *target;
        ListKey listkey;
        ListKey scope;
        ModMap::iterator it;
	int use = 1;
        cout << "[";
	for (it = manager.Modules.begin(); it != manager.Modules.end(); it++) {
		if( use == 1 ){
                        use = 0;
                } else {
                        cout << ",";
                }
		cout << "{\"name\": \"" << convert_string(  (*it).second->Name() ) << "\",\"desc\": \"" << convert_string( (*it).second->Description() ) << "\", \"type\": \"" << convert_string((*it).second->Type()) << "\" } ";

//                        fprintf(stderr, "[%s]\t - %s - %s \n", (*it).second->Name(), (*it).second->Description() , (*it).second->Type() );
        }
	cout << "]";
	return 0;
}
