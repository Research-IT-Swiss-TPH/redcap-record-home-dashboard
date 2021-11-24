## Documentation
This is the documentation for Record Home Dashboard external module.

### Editor / Render View
Record Home Dashboard module consists of two main views. The Editor View is used to create dashboards by adding a custom layout on top of the Grid System with applied Element Types. The Render View is embedded on the Record Home page of each record and renders the layout in the defined grid system with applied element types with the record as data source.

The Editor view is accessible through the External Modules navigation list as "Dashboard Editor". Per default any user assigned to the project can view and edit Dashboard Editor. To disable Editor View for specific users, Display Options can be assigned for different user-roles in the module configuration on project level.

The Render View is visible for any user on the Record Home Page of every Record. Cusomization of the Record Home Page for specific users can be assigned through Display Options in module configuration on project level.

![image](https://user-images.githubusercontent.com/75415872/143218228-10290d8b-c547-4b06-85f7-dbfda77ef688.png)


### Grid System
Record Home Dashboard uses a grid system to create the dashboard layout. This is common practise and ensures that content can be structured logically while maintaining readbility and design.

A grid system consists always of rows and columns.
There can be "n" rows and within each row can be "o" columns.
In each column can be further "p" elements.

![image](https://user-images.githubusercontent.com/75415872/143214704-c4514352-e529-435b-a372-623e5d1412ed.png)

*Above example grid system consists of `n=3` rows, where the first row has `o=3`, the second row `o=2` and the third row `o=1` columns. Elements within columns are not shown to keep things simple.*

### Element Types
Record Home Dashboard has different element types that can be included into each column. There currently available element types are:

- Text: Includes text with the option to add bold, italic or underlined styles
- Link: Includes a valid link to an external or internal target. Piping of smart variables are supported and allow linking to REDCap internal destinations, such as last-instance of a repeating instrument. Allow to add an icon, choose color, link title and window to be opened (same/new)
- List: Includes a list with `p` list elements where each list element consists of a key-value-pair. Piping of variables is supported
- Table: Includes a table of all entries for a repeating instrument. Filters by columns that can be defined

### Create a Dashboard

#### Add a row
The first step of creating

### Markdown

Markdown is a lightweight and easy-to-use syntax for styling your writing. It includes conventions for

```markdown
Syntax highlighted code block

# Header 1
## Header 2
### Header 3

- Bulleted
- List

1. Numbered
2. List

**Bold** and _Italic_ and `Code` text

[Link](url) and ![Image](src)
```

For more details see [Basic writing and formatting syntax](https://docs.github.com/en/github/writing-on-github/getting-started-with-writing-and-formatting-on-github/basic-writing-and-formatting-syntax).

### Jekyll Themes

Your Pages site will use the layout and styles from the Jekyll theme you have selected in your [repository settings](https://github.com/tertek/redcap-record-home-dashboard/settings/pages). The name of this theme is saved in the Jekyll `_config.yml` configuration file.

### Support or Contact

Having trouble with Pages? Check out our [documentation](https://docs.github.com/categories/github-pages-basics/) or [contact support](https://support.github.com/contact) and we’ll help you sort it out.
